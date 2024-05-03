<?php

namespace App\DataFixtures;

use App\Entity\Activity;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ActivityFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {


        $activity = new Activity();
        $activity->setName('Soirée E-Sport 2024');
        $activity->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Adipiscing enim eu turpis egestas pretium aenean. Ultrices dui sapien eget mi proin. Scelerisque varius morbi enim nunc faucibus a pellentesque. Pharetra convallis posuere morbi leo urna molestie at elementum. Velit ut tortor pretium viverra suspendisse potenti nullam ac tortor. Dui nunc mattis enim ut. Non tellus orci ac auctor augue mauris. Sem integer vitae justo eget. Dignissim suspendisse in est ante in. In fermentum et sollicitudin ac orci phasellus egestas tellus rutrum. Consequat semper viverra nam libero justo laoreet sit. Suspendisse potenti nullam ac tortor vitae purus faucibus ornare suspendisse. Etiam tempor orci eu lobortis. Nisl purus in mollis nunc sed id semper. Iaculis eu non diam phasellus vestibulum lorem sed.');
        $activity->setRegisterLimitDatetime(new DateTime('2024-05-05 12:00:00'));
        $activity->setStartDatetime(new DateTime('2024-05-06 12:00:00'));
        $activity->setDuration(new DateTime('000-00-00 02:30:00'));
        $activity->setLocation($this->getReference(LocationFixtures::LOCATION_REFERENCE));
        $activity->setMaxParticipants(10);
        $activity->setHost($this->getReference(UserFixtures::USER_REFERENCE));
        $activity->setCampus($this->getReference(CampusFixtures::CAMPUS_REFERENCE));
        $activity->setState($this->getReference(StateFixtures::STATE_REFERENCE));
        $manager->persist($activity);

        $activity->setName('Fete de Avril');
        $activity->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Adipiscing enim eu turpis egestas pretium aenean. Ultrices dui sapien eget mi proin. Scelerisque varius morbi enim nunc faucibus a pellentesque. Pharetra convallis posuere morbi leo urna molestie at elementum. Velit ut tortor pretium viverra suspendisse potenti nullam ac tortor. Dui nunc mattis enim ut. Non tellus orci ac auctor augue mauris. Sem integer vitae justo eget. Dignissim suspendisse in est ante in. In fermentum et sollicitudin ac orci phasellus egestas tellus rutrum. Consequat semper viverra nam libero justo laoreet sit. Suspendisse potenti nullam ac tortor vitae purus faucibus ornare suspendisse. Etiam tempor orci eu lobortis. Nisl purus in mollis nunc sed id semper. Iaculis eu non diam phasellus vestibulum lorem sed.');
        $activity->setRegisterLimitDatetime(new DateTime('2024-04-15 12:00:00'));
        $activity->setStartDatetime(new DateTime('2024-04-16 12:00:00'));
        $activity->setDuration(new DateTime('000-00-00 02:30:00'));
        $activity->setLocation($this->getReference(LocationFixtures::LOCATION_REFERENCE));
        $activity->setMaxParticipants(10);
        $activity->setHost($this->getReference(UserFixtures::USER_REFERENCE));
        $activity->setCampus($this->getReference(CampusFixtures::CAMPUS_REFERENCE));
        $activity->setState($this->getReference(StateFixtures::STATE_REFERENCE));
        $manager->persist($activity);

        $activity->setName('Festival de Musique');
        $activity->setDescription('Un festival de musique incroyable avec des artistes talentueux du monde entier. 🎶🎸🎹');
        $activity->setRegisterLimitDatetime(new DateTime('2024-06-01 12:00:00'));
        $activity->setStartDatetime(new DateTime('2024-06-05 14:00:00'));
        $activity->setDuration(new DateTime('000-00-00 06:00:00'));
        $activity->setLocation($this->getReference(LocationFixtures::LOCATION_REFERENCE));
        $activity->setMaxParticipants(100);
        $activity->setHost($this->getReference(UserFixtures::USER_REFERENCE));
        $activity->setCampus($this->getReference(CampusFixtures::CAMPUS_REFERENCE));
        $activity->setState($this->getReference(StateFixtures::STATE_REFERENCE));
        $manager->persist($activity);

        $activity->setName('Tournoi de Football');
        $activity->setDescription('Un tournoi de football amical ouvert à tous les étudiants. ⚽️🎉');
        $activity->setRegisterLimitDatetime(new DateTime('2024-04-15 18:00:00'));
        $activity->setStartDatetime(new DateTime('2024-04-16 10:00:00'));
        $activity->setDuration(new DateTime('000-00-00 08:00:00'));
        $activity->setLocation($this->getReference(LocationFixtures::LOCATION_REFERENCE));
        $activity->setMaxParticipants(50);
        $activity->setHost($this->getReference(UserFixtures::USER_REFERENCE));
        $activity->setCampus($this->getReference(CampusFixtures::CAMPUS_REFERENCE));
        $activity->setState($this->getReference(StateFixtures::STATE_REFERENCE));
        $manager->persist($activity);

        $activity->setName('Journée Portes Ouvertes');
        $activity->setDescription('Une journée portes ouvertes pour découvrir notre campus, nos programmes et rencontrer nos étudiants et professeurs. 🏫🎓');
        $activity->setRegisterLimitDatetime(new DateTime('2024-02-28 23:59:59'));
        $activity->setStartDatetime(new DateTime('2024-03-01 09:00:00'));
        $activity->setDuration(new DateTime('000-00-00 06:00:00'));
        $activity->setLocation($this->getReference(LocationFixtures::LOCATION_REFERENCE));
        $activity->setMaxParticipants(200);
        $activity->setHost($this->getReference(UserFixtures::USER_REFERENCE));
        $activity->setCampus($this->getReference(CampusFixtures::CAMPUS_REFERENCE));
        $activity->setState($this->getReference(StateFixtures::STATE_REFERENCE));
        $manager->persist($activity);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            LocationFixtures::class,
            UserFixtures::class,
            CampusFixtures::class,
        ];
    }
}
