<?php
/**
 * Created by PhpStorm.
 * User: mbrzuchalski
 * Date: 06.04.16
 * Time: 13:01
 */
namespace Madkom\NginxConfigurator;

use Ferno\Loco\ConcParser;
use Ferno\Loco\Grammar;
use Ferno\Loco\GreedyMultiParser;
use Ferno\Loco\GreedyStarParser;
use Ferno\Loco\LazyAltParser;
use Ferno\Loco\ParseFailureException;
use Ferno\Loco\RegexParser;
use Ferno\Loco\StringParser;
use Madkom\NginxConfigurator\Config\Events;
use Madkom\NginxConfigurator\Config\Http;
use Madkom\NginxConfigurator\Config\Location;
use Madkom\NginxConfigurator\Config\Server;
use Madkom\NginxConfigurator\Config\Upstream;
use Madkom\NginxConfigurator\Exception\GrammarException;
use Madkom\NginxConfigurator\Exception\UnrecognizedContextException;
use Madkom\NginxConfigurator\Node\Context;
use Madkom\NginxConfigurator\Node\Directive;
use Madkom\NginxConfigurator\Node\Literal;
use Madkom\NginxConfigurator\Node\Param;
use Madkom\NginxConfigurator\Node\RootNode;

/**
 * Class Parser
 * @package Madkom\NginxConfigurator
 * @author Michał Brzuchalski <m.brzuchalski@madkom.pl>
 */
class Parser extends Grammar
{
    /**
     * Holds parsed filename
     * @var string
     */
    protected $filename;
    /**
     * Holds parsed string
     * @var string
     */
    protected $content;

    /**
     * Parser constructor.
     */
    public function __construct()
    {
        parent::__construct('syntax', [
            'syntax' => new GreedyStarParser(new LazyAltParser(['directive', 'section'])),
            'sections' => new GreedyMultiParser('section', 0, 2),
            'section' => new ConcParser(
                [
                    'section-name',
                    new LazyAltParser(['space', 'opt-space']),
                    new LazyAltParser(['params', new LazyAltParser(['space', 'opt-space'])]),
                    new StringParser('{'),
                    new LazyAltParser(['space', 'opt-space']),
                    new GreedyMultiParser(new LazyAltParser(['directive', 'section']), 0, null),
                    new LazyAltParser(['space', 'opt-space']),
                    new StringParser('}'),
                    new LazyAltParser(['space', 'opt-space']),
                ],
                [$this, 'parseSection']
            ),
            'section-name' => new RegexParser('/^[a-z0-9\_]+/i'),

            'directives' => new GreedyMultiParser('directive', 0, null),
            'directive' => new LazyAltParser([
                new ConcParser([
                    'directive-name',
                    'semicolon',
                    new LazyAltParser(['space', 'opt-space']),
                ], [$this, 'parseDirective']),
                new ConcParser([
                    'directive-name',
                    'space',
                    'params',
                    'semicolon',
                    new LazyAltParser(['space', 'opt-space']),
                ], [$this, 'parseDirective'])
            ]),
            'directive-name' => new RegexParser('/^[a-z0-9\_]+/i'),

            'params' => new GreedyMultiParser(new ConcParser(['param', 'opt-space'], function ($param, $space) {
                return $param;
            }), 0, null),
            'param' => new LazyAltParser(['literal', 'param-name']),
            'param-name' => new RegexParser('/^[^\s\r\n\{\}\;\"\']+/i', function ($match) {
                return new Param($match);
            }),
            'literal' => new LazyAltParser([
                new RegexParser('/^"([^"]*)"/', function ($match0, $match1) {
                    return new Literal($match1);
                }),
                new RegexParser("/^'([^']*)'/", function ($match0, $match1) {
                    return new Literal($match1);
                })
            ]),

            'semicolon' => new StringParser(';', function () {
                return null;
            }),
            'space' => new GreedyStarParser('whitespace/comment', function () {
                return null;
            }),
            'whitespace/comment' => new LazyAltParser(['whitespace', 'comment'], function () {
                return null;
            }),
            'comment' => new RegexParser("/^#+([^\r\n]*)/", function () {
                return null;
            }),
            'whitespace' => new RegexParser("/^[ \t\r\n]+/"),
            'opt-space' => new RegexParser("/^[ \t\r\n]?/"),
            'eol' => new LazyAltParser([new StringParser("\r"), new StringParser("\n")], function () {
                return null;
            })
        ], function (array $nodes = []) {
            return new RootNode($nodes);
        });
    }

    /**
     * Parses config file
     * @param string $filename
     * @return mixed
     * @throws ParseFailureException
     */
    public function parseFile(string $filename) : RootNode
    {
        $this->content = null;
        $this->filename = $filename;

        return $this->parse(file_get_contents($filename));
    }

    /**
     * Parses string
     * @param string $string
     * @return mixed
     * @throws ParseFailureException
     */
    public function parse($string) : RootNode
    {
        $this->content = $string;
        $this->filename = null;

        return parent::parse($string);
    }

    /**
     * Parses section entries
     * @param string $section Section name
     * @param null $space0 Ignored
     * @param Param[] $params Params collection
     * @param null $open Ignored
     * @param null $space1 Ignored
     * @param Directive[] $directives Directives collection
     * @return Context
     * @throws GrammarException
     * @throws UnrecognizedContextException
     */
    protected function parseSection($section, $space0 = null, $params, $open = null, $space1 = null, $directives) : Context
    {
        switch ($section) {
            case 'server':
                return new Server($directives);
            
            case 'http':
                return new Http($directives);
            
            case 'location':
                $modifier = null;
                if (sizeof($params) == 2) {
                    list($modifier, $location) = $params;
                } elseif (sizeof($params) == 1) {
                    $location = $params[0];
                } else {
                    throw new GrammarException(
                        sprintf(
                            "Location context missing in %s",
                            $this->filename ? var_export($this->filename, true) : var_export($this->content, true)
                        )
                    );
                }
                return new Location($location, $modifier, $directives);
            
            case 'events':
                return new Events($directives);
            
            case 'upstream':
                list($upstream) = $params;
                return new Upstream($upstream, $directives);
        }

        throw new UnrecognizedContextException(
            sprintf(
                "Unrecognized context: {$section} found in %s",
                $this->filename ? var_export($this->filename, true) : var_export($this->content, true)
            )
        );
    }

    /**
     * Parses directive
     * @param string $name
     * @param null $space
     * @param array $params
     * @return Directive
     */
    protected function parseDirective(string $name, $space = null, $params = []) : Directive
    {
        return new Directive($name, is_null($params) ? [] : $params);
    }
}
