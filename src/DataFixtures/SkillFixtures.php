<?php

namespace App\DataFixtures;

use App\Entity\Skill;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SkillFixtures extends Fixture
{
    public const REFERENCE = 'skill_';
    public function __construct()
    {
    }

    public static function data(): array
    {
        return [
            'Canva',
            'Réseaux sociaux',
            'Java',
            'Gestion de projet',
            'HTML',
            'CSS',
            'JavaScript',
            'PHP',
            'MySQL',
            'Symfony',
            'Laravel',
            'React',
            'Vue.js',
            'Angular',
            'SEO',
            'Marketing de contenu',
            'Adobe Photoshop',
            'Adobe Illustrator',
            'UX/UI Design',
            'Communication',
            'Leadership',
            'Gestion du temps',
            'Résolution de problèmes',
            'Travail en équipe',
            'Méthodologies Agile',
            'Service client',
            'Vente',
            'Négociation',
            'Prise de parole en public',
            'Rédaction',
            'Organisation',
            'Gestion des événements',
            'Gestion des stocks',
            'Analyse de données',
            'Gestion des réseaux sociaux',
            'Rédaction de contenu',
            'Conception graphique',
            'Photographie',
            'Montage vidéo',
            'Conception de sites web',
            'Développement mobile',
            'Support technique',
            'Formation et développement',
            'Gestion des ressources humaines',
            'Gestion financière',
            'Analyse commerciale'
        ];
    }

    public function load(ObjectManager $manager)
    {
        foreach (self::data() as $i => $item) {
            $skill = new Skill();
            $skill->setName($item);
            $manager->persist($skill);
            $this->setReference(self::REFERENCE.$i, $skill);
        }
        $manager->flush();
    }
}