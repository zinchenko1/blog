<?php

namespace App\Menu;

use App\Entity\Category;
use Doctrine\Common\Persistence\ManagerRegistry;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

class Builder
{
    private $factory;
    private $registry;

    public function __construct(FactoryInterface $factory, ManagerRegistry $registry)
    {
        $this->factory = $factory;
        $this->registry = $registry;
    }

    /**
     * @return ItemInterface
     */
    public function MainMenu(): ItemInterface
    {
        $counter = 1;
        $categories = $this->registry->getManager()->getRepository(Category::class)
            ->findBy(['isMain' => Category::MAIN]);
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav-menu nav navbar-nav');
        $menu->addChild('Home', ['route' => 'site_index']);
        foreach ($categories as $category) {
            $class = implode('-', ['cat', $counter]);
            $params = ['categorySlug' => $category->getSlug()];
            $menu
                ->addChild($category->getTitle(), ['route' => 'category_posts', 'routeParameters' => $params])
                ->setAttribute('class', $class);
            $counter++;
        }

        return $menu;
    }

    /**
     * @return ItemInterface
     */
    public function MobileMenu(): ItemInterface
    {
        $categories = $this->registry->getManager()->getRepository(Category::class)
            ->findBy(['isMain' => Category::MAIN]);
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav-aside-menu');
        $menu->addChild('Home', ['route' => 'site_index']);

        foreach ($categories as $category) {
            $params = ['categorySlug' => $category->getSlug()];
            $menu->addChild($category->getTitle(), ['route' => 'category_posts', 'routeParameters' => $params]);
        }

        return $menu;
    }
}
