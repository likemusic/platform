<?php

declare(strict_types=1);

namespace Orchid\Screen\Layouts;

use Throwable;
use Orchid\Screen\Builder;
use Orchid\Screen\Repository;
use Illuminate\Contracts\View\Factory;
use Psr\Container\ContainerInterface;

/**
 * Class Rows.
 */
abstract class Rows extends Base
{
    /**
     * @var string
     */
    protected $template = 'platform::layouts.row';

    /**
     * @var Repository
     */
    protected $query;

    /**
     * @var int
     */
    protected $with = 100;

    /**
     * Base constructor.
     *
     * @param ContainerInterface $container
     * @param Base[] $layouts
     */
    public function __construct(ContainerInterface $container, array $layouts = [])
    {
        $this->layouts = $layouts;

        parent::__construct($container);
    }

    /**
     * @param Repository $repository
     *
     * @throws Throwable
     *
     * @return Factory|\Illuminate\View\View
     */
    public function build(Repository $repository)
    {
        if (! $this->checkPermission($this, $repository)) {
            return;
        }

        $this->query = $repository;
        $form = new Builder($this->fields(), $repository);

        return view($this->template, [
            'with' => $this->with,
            'form' => $form->generateForm(),
        ]);
    }

    /**
     * @param int $with
     *
     * @return $this
     */
    public function with(int $with = 100) : self
    {
        $this->with = $with;

        return $this;
    }

    /**
     * @return array
     */
    abstract protected function fields(): array;
}
