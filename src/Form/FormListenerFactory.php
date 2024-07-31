<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\String\Slugger\AsciiSlugger;

class FormListenerFactory
{
    public function autoSlug(string $field): callable
    {
        return function (PreSubmitEvent $event) use ($field){
            $data = $event->getData();
            if (empty($data['slug'])){
                $slugger = new AsciiSlugger();
                $data['slug'] = strtolower($slugger->slug($data[$field]));
                $event->setData($data);
            }
        };
    }

    public function autoTime(): callable
    {
        return function (PostSubmitEvent $event){
            $data = $event->getData();
            $data->setUpdatedAt(new \DateTimeImmutable());
            if (!$data->getId()){
                $data->setCreatedAt(new \DateTimeImmutable());
            }
        };
    }
}
