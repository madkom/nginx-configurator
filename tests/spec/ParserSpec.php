<?php

namespace spec\Madkom\NginxConfigurator;

use Ferno\Loco\ParseFailureException;
use Madkom\NginxConfigurator\Config\Location;
use Madkom\NginxConfigurator\Config\Server;
use Madkom\NginxConfigurator\Node\Context;
use Madkom\NginxConfigurator\Node\Directive;
use Madkom\NginxConfigurator\Node\Node;
use Madkom\NginxConfigurator\Node\RootNode;
use Madkom\NginxConfigurator\Parser;
use PhpSpec\ObjectBehavior;
use PHPUnit_Framework_Assert as Assert;
use Prophecy\Argument;
use Traversable;

/**
 * Class ParserSpec
 * @package spec\Madkom\NginxConfigurator
 * @author Michał Brzuchalski <m.brzuchalski@madkom.pl>
 * @mixin Parser
 */
class ParserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Madkom\NginxConfigurator\Parser');
    }

    /**
     * @throws ParseFailureException
     */
    function it_can_parse_directive()
    {
        /** @var RootNode $root */
        $root = $this->parse(<<<EOF
internal;
EOF
);
        $root->shouldReturnAnInstanceOf(RootNode::class);

        $directives = $root->search(function (Node $node) {
            return $node;
        })->getWrappedObject();
        /** @var Directive $directive */
        foreach ($directives as $directive) {
            break;
        }
        Assert::assertEquals($directive->getName(), 'internal');
        Assert::assertInstanceOf(Traversable::class, $directive->getParams());
    }

    function it_can_parse_multiple_directives_with_params()
    {
        $root = $this->parse(<<<EOF
internal;
## comment with two hashes
sendfile off; # comment after directive
#comment beetween directives
set \$true 1;
EOF
        );
        $root->shouldReturnAnInstanceOf(RootNode::class);

        $directives = $root->search(function (Node $node) {
            return $node;
        })->getWrappedObject();
        /** @var Directive $directive */
        foreach ($directives as $index => $directive) {
            switch ($index) {
                case 0:
                    Assert::assertEquals($directive->getName(), 'internal');
                    Assert::assertInstanceOf(Traversable::class, $directive->getParams());
                    break;
                case 1:
                    Assert::assertEquals($directive->getName(), 'sendfile');
                    Assert::assertInstanceOf(Traversable::class, $directive->getParams());
                    break;
                case 2:
                    Assert::assertEquals($directive->getName(), 'set');
                    Assert::assertInstanceOf(Traversable::class, $directive->getParams());
                    break;
            }
        }
    }

    function it_can_parse_multiple_directives_with_params_in_context()
    {
        $root = $this->parse(<<<EOF
server {
    internal;
    ## comment with two hashes
    sendfile off; # comment after directive
    #comment beetween directives
    set \$true 1;
}
EOF
        );
        $root->shouldReturnAnInstanceOf(RootNode::class);

        $contexts = $root->search(function (Node $node) {
            return $node;
        })->getWrappedObject();
        /** @var Context $context */
        foreach ($contexts as $index => $context) {
            switch ($index) {
                case 0:
                    Assert::assertInstanceOf(Server::class, $context);
                    /** @var Directive $directive */
                    foreach ($context as $index => $directive) {
                        switch ($index) {
                            case 0:
                                Assert::assertEquals('internal', $directive->getName());
                                break;
                            case 1:
                                Assert::assertEquals('sendfile', $directive->getName());
                                break;
                            case 2:
                                Assert::assertEquals('set', $directive->getName());
                        }
                    }
                    break;
            }
        }
    }

    function it_can_parse_multiple_directives_with_params_in_multiple_contexts()
    {
        $root = $this->parse(<<<EOF
server {
    internal;
    ## comment with two hashes
    sendfile off; # comment after directive
    #comment beetween directives
    set \$true 1;
    
    location ~ /app {
        set \$true 0;
    }
}

sendfile off;

events {
    sendfile off;
}
EOF
        );
        $root->shouldReturnAnInstanceOf(RootNode::class);

        $contexts = $root->search(function (Node $node) {
            return $node;
        })->getWrappedObject();
        /** @var Context $context */
        foreach ($contexts as $index => $context) {
            switch ($index) {
                case 0:
                    Assert::assertInstanceOf(Server::class, $context);
                    /** @var Directive $directive */
                    foreach ($context as $index => $directive) {
                        switch ($index) {
                            case 0:
                                Assert::assertEquals('internal', $directive->getName());
                                break;
                            case 1:
                                Assert::assertEquals('sendfile', $directive->getName());
                                break;
                            case 2:
                                Assert::assertEquals('set', $directive->getName());
                                break;
                            case 3:
                                Assert::assertInstanceOf(Location::class, $directive);
                                break;
                        }
                    }
                    break;

                case 1:
                    Assert::assertInstanceOf(Directive::class, $context);
                    Assert::assertEquals('sendfile', $context->getName());
                    break;

            }
        }
    }
}
