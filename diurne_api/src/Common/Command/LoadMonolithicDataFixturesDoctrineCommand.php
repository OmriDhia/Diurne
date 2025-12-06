<?php

namespace App\Common\Command;

use LogicException;
use App\User\DataFixtures\UserFixtures;
use App\Menu\DataFixtures\MenuFixtures;
use App\Contact\Repository\AddressTypeRepository;
use App\Contact\Repository\AttributionStatusRepository;
use App\Contact\Repository\CustomerGroupRepository;
use App\Contact\Repository\CustomerRepository;
use App\Contact\Repository\IntermediaryTypeRepository;
use App\Event\Repository\EventNomenclatureRepository;
use App\Setting\Repository\CountryRepository;
use App\Setting\Repository\DiscountRuleRepository;
use App\Setting\Repository\LanguageRepository;
use App\User\Repository\GenderRepository;
use App\User\Repository\PermissionRepository;
use App\User\Repository\ProfileRepository;
use App\User\Repository\UserRepository;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\DBAL\Sharding\PoolingShardConnection;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader as DataFixturesLoader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class LoadMonolithicDataFixturesDoctrineCommand extends Command
{
    private $modules = [
        'User',
        'Menu',
        'Setting',
        'Contact',
        'Event',
        'Contremarque',
        'Workshop',
        'checkingList',
    ];

    public function __construct(
        private readonly ContainerInterface          $container,
        private readonly UserPasswordHasherInterface $hasher,
                                                     $name,
        private readonly ?TranslatorInterface        $translator,
        private readonly PermissionRepository        $permissionRepository,
        private readonly ProfileRepository           $profileRepository,
        private readonly GenderRepository            $genderRepository,
        private readonly DiscountRuleRepository      $discountRuleRepository,
        private readonly CustomerGroupRepository     $customerGroupRepository,
        private readonly AddressTypeRepository       $addressTypeRepository,
        private readonly UserRepository              $userRepository,
        private readonly IntermediaryTypeRepository  $intermediaryTypeRepository,
        private readonly EventNomenclatureRepository $eventNomenclatureRepository,
        private readonly LanguageRepository          $languageRepository,
        private readonly AttributionStatusRepository $attributionStatusRepository,
        private readonly CountryRepository           $countryRepository,
        private readonly CustomerRepository          $customerRepository
    )
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->setName('doctrine:monolithic:fixtures:load')
            ->setDescription('Load data fixtures to your database')
            ->addOption('fixtures', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'The directory to load data fixtures from.')
            ->addOption('append', null, InputOption::VALUE_NONE, 'Append the data fixtures instead of deleting all data from the database first.')
            ->addOption('em', null, InputOption::VALUE_REQUIRED, 'The entity manager to use for this command.')
            ->addOption('group', null, InputOption::VALUE_REQUIRED, 'group bu module.')
            ->addOption('shard', null, InputOption::VALUE_REQUIRED, 'The shard connection to use for this command.')
            ->addOption('purge-with-truncate', null, InputOption::VALUE_NONE, 'Purge data by using a database-level TRUNCATE statement')
            ->setHelp(
                <<<EOT
            The <info>%command.name%</info> command loads data fixtures from your application:
            
              <info>php %command.full_name%</info>
            
            You can also optionally specify the path to fixtures with the <info>--fixtures</info> option:
            
              <info>php %command.full_name% --fixtures=/path/to/fixtures1 --fixtures=/path/to/fixtures2</info>
            
            Fixtures are services that are tagged with <comment>doctrine.fixture.orm</comment>.
            
            If you want to append the fixtures instead of flushing the database first you can use the <comment>--append</comment> option:
            
              <info>php %command.full_name%</info> <comment>--append</comment>
            
            By default Doctrine Data Fixtures uses DELETE statements to drop the existing rows from the database.
            If you want to use a TRUNCATE statement instead you can use the <comment>--purge-with-truncate</comment> flag:
            
              <info>php %command.full_name%</info> <comment>--purge-with-truncate</comment>
            
            EOT
            );
        $this->setDescription('Load monolithic data fixtures.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1');
        $ui = new SymfonyStyle($input, $output);

        /** @var $doctrine \Doctrine\Common\Persistence\ManagerRegistry */
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getManager($input->getOption('em'));
        $group = $input->getOption('group');

        if (!$input->getOption('append')) {
            $ui->ask('Careful, database will be purged. Do you want to continue y/N ?', false);
        }

        if ($input->getOption('shard')) {
            if (!$em->getConnection() instanceof PoolingShardConnection) {
                throw new LogicException(sprintf('Connection of EntityManager "%s" must implement shards configuration.', $input->getOption('em')));
            }

            $em->getConnection()->connect($input->getOption('shard'));
        }

        $dirOrFile = $input->getOption('fixtures');

        if ($dirOrFile) {
            $paths = is_array($dirOrFile) ? $dirOrFile : [$dirOrFile];
        } else {
            /** @var $kernel \Symfony\Component\HttpKernel\KernelInterface */
            $kernel = $this->getApplication()->getKernel();
            $paths = [$kernel->getProjectDir() . '/src/DataFixtures'];

            foreach ($kernel->getBundles() as $bundle) {
                $paths[] = $bundle->getPath() . '/DataFixtures';
            }
            if (!empty($this->modules)) {
                foreach ($this->modules as $module) {
                    if (!empty($group) && $group != $module) {
                        continue;
                    }
                    $paths[] = $kernel->getProjectDir() . '/src/' . $module . '/DataFixtures';
                }
            }
        }

        $loader = new DataFixturesLoader($this->getContainer());
        foreach ($paths as $path) {
            if (is_dir($path)) {
                $loader->loadFromDirectory($path);
            } elseif (is_file($path)) {
                $loader->loadFromFile($path);
            }
        }

        $fixtures = $loader->getFixtures();
        if (!empty($fixtures['App\Contact\DataFixtures\CommercialFixtures'])) {
            $fixtures['App\Contact\DataFixtures\CommercialFixtures']->setHasher($this->hasher);
            $fixtures['App\Contact\DataFixtures\CommercialFixtures']->setTranslator($this->translator);
            $fixtures['App\Contact\DataFixtures\CommercialFixtures']->setPermissionRepository($this->permissionRepository);
        }
        if (!empty($fixtures[UserFixtures::class])) {
            $fixtures[UserFixtures::class]->setHasher($this->hasher);
            $fixtures[UserFixtures::class]->setTranslator($this->translator);
            $fixtures[UserFixtures::class]->setPermissionRepository($this->permissionRepository);
        }
        if (!empty($fixtures['App\Setting\DataFixtures\DiscountFixtures'])) {
            $fixtures['App\Setting\DataFixtures\DiscountFixtures']->setLanguageRepository($this->languageRepository);
        }
        if (!empty($fixtures['App\Setting\DataFixtures\LanguageFixtures'])) {
            $fixtures['App\Setting\DataFixtures\LanguageFixtures']->setLanguageRepository($this->languageRepository);
        }
        if (!empty($fixtures[MenuFixtures::class])) {
            $fixtures[MenuFixtures::class]->setPermissionRepository($this->permissionRepository);
        }
        if (!$fixtures) {
            $ui->error(sprintf('Could not find any fixtures to load in: %s', "\n\n- " . implode("\n- ", $paths)));

            return 1;
        }
        $purger = new ORMPurger($em);
        $purger->setPurgeMode($input->getOption('purge-with-truncate') ? ORMPurger::PURGE_MODE_TRUNCATE : ORMPurger::PURGE_MODE_DELETE);
        $executor = new ORMExecutor($em, $purger);
        $executor->setLogger(function ($message) use ($ui) {
            $ui->text(sprintf('  <comment>></comment> <info>%s</info>', $message));
        });
        $executor->execute($fixtures, $input->getOption('append'));

        return 0;
    }

    private function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}
