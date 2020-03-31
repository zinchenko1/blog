<?php

namespace App\Menu;

use App\Entity\Category;
use App\Entity\Tag;
use Doctrine\Common\Persistence\ManagerRegistry;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Builder  extends AbstractController
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

    /**
     * @return ItemInterface
     */
    public function SidebarMenu(): ItemInterface
    {
        $counter = 1;
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $categories = $repository->getRelatedPosts();

        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav-sidebar-menu');

        foreach ($categories as $key => $category) {
            $count = count($category->getPosts());
            $class = implode('-', ['cat', $counter]);
            $params = ['categorySlug' => $category->getSlug()];
            $menu->addChild($category->getTitle(), [
                'route' => 'category_posts',
                'routeParameters' => $params,
                'label' => $category->getTitle().'<span>'.$count.'</span></a>',
                'extras' => [
                    'safe_label' => true
                ]
            ])
                ->setAttributes(['class'=> $class]);
            $counter++;
        }

        return $menu;
    }

    /**
     * @return ItemInterface
     */
    public function TagsMenu(): ItemInterface
    {
        $repository = $this->getDoctrine()->getRepository(Tag::class);
        $tags = $repository->findBy([], ['id'=> "DESC"], 10);

        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav-tags-menu');

        $menu->addChild('ALL TAGS', ['route' => 'tag_index'])->setAttributes(['class'=> 'all-tags']);;

        foreach ($tags as $tag) {
            $params = ['tagSlug' => $tag->getSlug()];
            $menu->addChild($tag->getName(), [
                'route' => 'tag_posts',
                'routeParameters' => $params,
            ]);
        }

        return $menu;
    }
}
