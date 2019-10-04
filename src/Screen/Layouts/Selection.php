<?php

declare(strict_types=1);

namespace Orchid\Screen\Layouts;

use Orchid\Filters\Filter;
use Orchid\Screen\Repository;
use Illuminate\Contracts\View\Factory;
use Psr\Container\ContainerInterface;

/**
 * Class Selection.
 */
abstract class Selection extends Base
{
    /**
     * Drop down filters.
     */
    public const TEMPLATE_DROP_DOWN = 'platform::layouts.selection';

    /**
     * Line filters.
     */
    public const TEMPLATE_LINE = 'platform::layouts.filter';

    /**
     * @var string
     */
    public $template = self::TEMPLATE_DROP_DOWN;

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
     * @return Factory|\Illuminate\View\View|mixed
     */
    public function build(Repository $repository)
    {
        if (! $this->checkPermission($this, $repository)) {
            return;
        }

        $filters = collect($this->filters());
        $count = $filters->count();

        if ($count === 0) {
            return;
        }

        foreach ($filters as $key => $filter) {
            $filters[$key] = new $filter();
        }

        return view($this->template, [
            'filters' => $filters,
            'chunk'   => ceil($count / 4),
        ]);
    }

    /**
     * @return Filter[]
     */
    abstract public function filters(): array;
}
