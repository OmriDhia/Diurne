<?php

namespace App\Menu\DataFixtures;

use DateTimeImmutable;
use App\Menu\Entity\Menu;
use App\User\Repository\PermissionRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Contracts\Translation\TranslatorInterface;

class MenuFixtures extends Fixture
{
    private readonly TranslatorInterface $translator;
    private PermissionRepository $permissionRepository;

    public function load(ObjectManager $manager): void
    {
        $contacts = new Menu();
        $contacts->setActive(1);
        $contacts->setRoute('contacts');
        $contacts->setParentId(0);
        $contacts->setName('Contacts');
        $contacts->setPosition(0);
        $contacts->setSlug('contacts');
        $contacts->setCreatedAt(new DateTimeImmutable());
        $contacts->setUpdatedAt(new DateTimeImmutable());
        $manager->persist($contacts);
        $manager->flush();
        $agent = new Menu();
        $agent->setActive(1);
        $agent->setRoute('contacts');
        $agent->setParentId($contacts->getId());
        $agent->setSlug('contacts');
        $agent->setName('Contacts');
        $agent->setPosition(0);
        $agent->setCreatedAt(new DateTimeImmutable());
        $agent->setUpdatedAt(new DateTimeImmutable());
        $manager->persist($agent);
        $manager->flush();
        $projet = new Menu();
        $projet->setActive(1);
        $projet->setRoute('#');
        $projet->setName('Projet');
        $projet->setPosition(1);
        $projet->setSlug('projet');
        $projet->setParentId(0);
        $projet->setCreatedAt(new DateTimeImmutable());
        $projet->setUpdatedAt(new DateTimeImmutable());
        $manager->persist($projet);
        $manager->flush();
        $tapis = new Menu();
        $tapis->setActive(1);
        $tapis->setRoute('#');
        $tapis->setParentId(0);
        $tapis->setSlug('Tapis');
        $tapis->setName('Tapis');
        $tapis->setPosition(2);
        $tapis->setCreatedAt(new DateTimeImmutable());
        $tapis->setUpdatedAt(new DateTimeImmutable());
        $manager->persist($tapis);
        $manager->flush();
        $tresorerie = new Menu();
        $tresorerie->setActive(1);
        $tresorerie->setName('Trésorerie');
        $tresorerie->setSlug('tresorerie');
        $tresorerie->setPosition(3);
        $tresorerie->setParentId(0);
        $tresorerie->setRoute('#');
        $tresorerie->setCreatedAt(new DateTimeImmutable());
        $tresorerie->setUpdatedAt(new DateTimeImmutable());
        $manager->persist($tresorerie);
        $manager->flush();
        $contremarque = new Menu();
        $contremarque->setActive(1);
        $contremarque->setRoute('contremarques');
        $contremarque->setParentId($projet->getId());
        $contremarque->setName('Contremarques');
        $contremarque->setPosition(0);
        $contremarque->setSlug('contremarques');
        $contremarque->setCreatedAt(new DateTimeImmutable());
        $contremarque->setUpdatedAt(new DateTimeImmutable());
        $manager->persist($contremarque);
        $manager->flush();
        $devis = new Menu();
        $devis->setActive(1);
        $devis->setRoute('devis');
        $devis->setParentId($projet->getId());
        $devis->setName('Devis');
        $devis->setPosition(1);
        $devis->setSlug('devis');
        $devis->setCreatedAt(new DateTimeImmutable());
        $devis->setUpdatedAt(new DateTimeImmutable());
        $manager->persist($devis);
        $manager->flush();
        $di = new Menu();
        $di->setActive(1);
        $di->setRoute('di_list');
        $di->setParentId($projet->getId());
        $di->setName('Suivi des DI');
        $di->setPosition(2);
        $di->setSlug('suivi-des-di');
        $di->setCreatedAt(new DateTimeImmutable());
        $di->setUpdatedAt(new DateTimeImmutable());
        $manager->persist($di);
        $manager->flush();
        $orders = new Menu();
        $orders->setActive(1);
        $orders->setRoute('orders');
        $orders->setParentId($projet->getId());
        $orders->setName('Commande client');
        $orders->setPosition(3);
        $orders->setSlug('commande-client');
        $orders->setCreatedAt(new DateTimeImmutable());
        $orders->setUpdatedAt(new DateTimeImmutable());
        $manager->persist($orders);
        $manager->flush();
        $invoices = new Menu();
        $invoices->setActive(1);
        $invoices->setRoute('invoices');
        $invoices->setParentId($projet->getId());
        $invoices->setName('Facture client');
        $invoices->setPosition(4);
        $invoices->setSlug('facture-client');
        $invoices->setCreatedAt(new DateTimeImmutable());
        $invoices->setUpdatedAt(new DateTimeImmutable());
        $manager->persist($invoices);
        $manager->flush();
        $images = new Menu();
        $images->setActive(1);
        $images->setRoute('images');
        $images->setParentId($tapis->getId());
        $images->setName('Images');
        $images->setPosition(0);
        $images->setSlug('images');
        $images->setCreatedAt(new DateTimeImmutable());
        $images->setUpdatedAt(new DateTimeImmutable());
        $manager->persist($images);
        $manager->flush();
        $tp = new Menu();
        $tp->setActive(1);
        $tp->setRoute('tapis');
        $tp->setParentId($tapis->getId());
        $tp->setName('Tapis');
        $tp->setPosition(1);
        $tp->setSlug('tapis-inner');
        $tp->setCreatedAt(new DateTimeImmutable());
        $tp->setUpdatedAt(new DateTimeImmutable());
        $manager->persist($tp);
        $manager->flush();
        $supplierInvoice = new Menu();
        $supplierInvoice->setActive(1);
        $supplierInvoice->setRoute('supplier_invoices');
        $supplierInvoice->setParentId($tapis->getId());
        $supplierInvoice->setName('Factures fournisseur');
        $supplierInvoice->setPosition(2);
        $supplierInvoice->setSlug('supplier-invoices');
        $supplierInvoice->setCreatedAt(new DateTimeImmutable());
        $supplierInvoice->setUpdatedAt(new DateTimeImmutable());
        $manager->persist($supplierInvoice);
        $manager->flush();
        $contactPermissions = $this->permissionRepository->findBy(['entity' => 'Contact']);
        if (count($contactPermissions)) {
            foreach ($contactPermissions as $contactPermission) {
                $contacts->addPermission($contactPermission);
                $agent->addPermission($contactPermission);
                $manager->persist($agent);
                $manager->persist($contacts);
            }
        }
        $contremarquePermissions = $this->permissionRepository->findBy(['entity' => 'Contremarque']);
        if (count($contremarquePermissions)) {
            foreach ($contremarquePermissions as $contremarquePermission) {
                $contremarque->addPermission($contremarquePermission);
                $manager->persist($contremarque);
            }
        }
        $devisPermissions = $this->permissionRepository->findBy(['entity' => 'Quote']);
        if (count($devisPermissions)) {
            foreach ($devisPermissions as $devisPermission) {
                $devis->addPermission($devisPermission);
                $manager->persist($devis);
            }
        }
        $diPermissions = $this->permissionRepository->findBy(['entity' => 'DI']);
        if (count($diPermissions)) {
            foreach ($diPermissions as $diPermission) {
                $di->addPermission($diPermission);
                $manager->persist($di);
            }
        }
        $ordersPermissions = $this->permissionRepository->findBy(['entity' => 'Order']);
        if (count($ordersPermissions)) {
            foreach ($ordersPermissions as $ordersPermission) {
                $orders->addPermission($ordersPermission);
                $manager->persist($orders);
            }
        }
        $invoicesPermissions = $this->permissionRepository->findBy(['entity' => 'Invoice']);
        if (count($invoicesPermissions)) {
            foreach ($invoicesPermissions as $invoicesPermission) {
                $invoices->addPermission($invoicesPermission);
                $manager->persist($invoices);
            }
        }

        // project menu
        if (count($contremarquePermissions)) {
            foreach ($contremarquePermissions as $contremarquePermission) {
                $projet->addPermission($contremarquePermission);
                $manager->persist($projet);
            }
        }
        if (count($devisPermissions)) {
            foreach ($devisPermissions as $devisPermission) {
                $projet->addPermission($devisPermission);
                $manager->persist($projet);
            }
        }
        if (count($diPermissions)) {
            foreach ($diPermissions as $diPermission) {
                $projet->addPermission($diPermission);
                $manager->persist($projet);
            }
        }

        if (count($ordersPermissions)) {
            foreach ($ordersPermissions as $ordersPermission) {
                $projet->addPermission($ordersPermission);
                $manager->persist($projet);
            }
        }

        if (count($invoicesPermissions)) {
            foreach ($invoicesPermissions as $invoicesPermission) {
                $projet->addPermission($invoicesPermission);
                $manager->persist($projet);
            }
        }

        // tapis menu
        $imagesPermissions = $this->permissionRepository->findBy(['entity' => 'Image']);
        if (count($imagesPermissions)) {
            foreach ($imagesPermissions as $imagesPermission) {
                $images->addPermission($imagesPermission);
                $manager->persist($images);
            }
        }
        $tapisPermissions = $this->permissionRepository->findBy(['entity' => 'Carpet']);
        if (count($tapisPermissions)) {
            foreach ($tapisPermissions as $tapisPermission) {
                $tp->addPermission($tapisPermission);
                $manager->persist($tp);
            }
        }

        if (count($invoicesPermissions)) {
            foreach ($invoicesPermissions as $invoicesPermission) {
                $supplierInvoice->addPermission($invoicesPermission);
                $manager->persist($supplierInvoice);
            }
        }

        if (count($imagesPermissions)) {
            foreach ($imagesPermissions as $imagesPermission) {
                $tapis->addPermission($imagesPermission);
                $manager->persist($tapis);
            }
        }
        if (count($tapisPermissions)) {
            foreach ($tapisPermissions as $tapisPermission) {
                $tapis->addPermission($tapisPermission);
                $manager->persist($tapis);
            }
        }
        if (count($invoicesPermissions)) {
            foreach ($invoicesPermissions as $invoicesPermission) {
                $tapis->addPermission($invoicesPermission);
                $manager->persist($tapis);
            }
        }

        $treasuryPermissions = $this->permissionRepository->findBy(['entity' => 'Treasury']);
        if (count($treasuryPermissions)) {
            foreach ($treasuryPermissions as $treasuryPermission) {
                $tresorerie->addPermission($treasuryPermission);
                $manager->persist($tresorerie);
            }
        }
        $manager->flush();
    }

    public function setPermissionRepository(PermissionRepository $permissionRepository): static
    {
        $this->permissionRepository = $permissionRepository;

        return $this;
    }
}
