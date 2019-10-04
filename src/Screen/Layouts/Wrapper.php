<?php

declare(strict_types=1);

namespace Orchid\Screen\Layouts;

use Illuminate\Support\Arr;
use Orchid\Screen\Repository;
use Psr\Container\ContainerInterface;

/**
 * Class Wrapper.
 */
abstract class Wrapper extends Base
{
    /**
     * Wrapper constructor.
     *
     * @param ContainerInterface $container
     * @param string $template
     * @param Base[] $layouts
     */
    public function __construct(ContainerInterface $container, string $template, array $layouts = [])
    {
        $this->template = $template;
        $this->layouts = $layouts;

        parent::__construct($container);
    }

    /**
     * @param Repository $repository
     *
     * @return \Illuminate\Contracts\View\View|void
     */
    public function build(Repository $repository)
    {
        if (! $this->checkPermission($this, $repository)) {
            return;
        }

        $build = collect($this->layouts)
            ->map(function ($layout, $key) use ($repository) {
                $items = $this->buildChild(Arr::wrap($layout), $key, $repository);

                return ! is_array($layout) ? reset($items)[0] : reset($items);
            })
            ->merge($repository->all())
            ->all();

        return view($this->template, $build);
    }
}
