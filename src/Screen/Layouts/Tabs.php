<?php

declare(strict_types=1);

namespace Orchid\Screen\Layouts;

use Orchid\Screen\Repository;
use Psr\Container\ContainerInterface;

/**
 * Class Tabs.
 */
abstract class Tabs extends Base
{
    /**
     * @var string
     */
    public $template = 'platform::layouts.tabs';

    /**
     * Base constructor.
     *
     * @param ContainerInterface $container
     * @param Base[] $layouts
     */
    public function __construct(ContainerInterface $container,array $layouts = [])
    {
        $this->layouts = $layouts;

        parent::__construct($container);
    }

    /**
     * @param Repository $repository
     *
     * @return mixed
     */
    public function build(Repository $repository)
    {
        return $this->buildAsDeep($repository);
    }
}
