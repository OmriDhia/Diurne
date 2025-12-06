<?php

namespace App\Menu\Command;

use DateTimeImmutable;
use App\Menu\Entity\Menu;
use App\Menu\Repository\MenuRepository;
use App\User\Repository\PermissionRepository;
use App\User\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ResetMenuCommand extends Command
{
    private readonly UserRepository $userRepository;

    public function __construct(
        private readonly MenuRepository       $menuRepository,
        private readonly PermissionRepository $permissionRepository
    )
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('app:reset-menus');
        $this->setDescription('Reset Menus.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $menus = $this->menuRepository->findAll();

        foreach ($menus as $menu) {
            $this->menuRepository->remove($menu);
        }
        $contacts = new Menu();
        $contacts->setActive(1);
        $contacts->setRoute('#');
        $contacts->setName('Contacts');
        $contacts->setPosition(0);
        $contacts->setSlug('contacts');
        $contacts->setParentId(0);
        $contacts->setCreatedAt(new DateTimeImmutable());
        $contacts->setUpdatedAt(new DateTimeImmutable());
        $this->menuRepository->persist($contacts);
        $this->menuRepository->flush();
        $listContacts = new Menu();
        $listContacts->setActive(1);
        $listContacts->setRoute('contacts');
        $listContacts->setParentId($contacts->getId());
        $listContacts->setName('Contacts');
        $listContacts->setPosition(0);
        $listContacts->setSlug('contacts');
        $listContacts->setCreatedAt(new DateTimeImmutable());
        $listContacts->setUpdatedAt(new DateTimeImmutable());
        $this->menuRepository->persist($listContacts);
        $this->menuRepository->flush();
        $projet = new Menu();
        $projet->setActive(1);
        $projet->setRoute('#');
        $projet->setName('Projet');
        $projet->setPosition(1);
        $projet->setSlug('projet');
        $projet->setParentId(0);
        $projet->setCreatedAt(new DateTimeImmutable());
        $projet->setUpdatedAt(new DateTimeImmutable());
        $this->menuRepository->persist($projet);
        $this->menuRepository->flush();
        $tapis = new Menu();
        $tapis->setActive(1);
        $tapis->setRoute('#');
        $tapis->setParentId(0);
        $tapis->setSlug('Tapis');
        $tapis->setName('Tapis');
        $tapis->setPosition(2);
        $tapis->setCreatedAt(new DateTimeImmutable());
        $tapis->setUpdatedAt(new DateTimeImmutable());
        $this->menuRepository->persist($tapis);
        $this->menuRepository->flush();
        $tresorerie = new Menu();
        $tresorerie->setActive(1);
        $tresorerie->setName('Trésorerie');
        $tresorerie->setSlug('tresorerie');
        $tresorerie->setPosition(3);
        $tresorerie->setParentId(0);
        $tresorerie->setRoute('#');
        $tresorerie->setCreatedAt(new DateTimeImmutable());
        $tresorerie->setUpdatedAt(new DateTimeImmutable());
        $this->menuRepository->persist($tresorerie);
        $this->menuRepository->flush();
        $contremarque = new Menu();
        $contremarque->setActive(1);
        $contremarque->setRoute('contremarques');
        $contremarque->setParentId($projet->getId());
        $contremarque->setName('Contremarques');
        $contremarque->setPosition(0);
        $contremarque->setSlug('contremarques');
        $contremarque->setCreatedAt(new DateTimeImmutable());
        $contremarque->setUpdatedAt(new DateTimeImmutable());
        $this->menuRepository->persist($contremarque);
        $this->menuRepository->flush();
        $devis = new Menu();
        $devis->setActive(1);
        $devis->setRoute('devis');
        $devis->setParentId($projet->getId());
        $devis->setName('Devis');
        $devis->setPosition(1);
        $devis->setSlug('devis');
        $devis->setCreatedAt(new DateTimeImmutable());
        $devis->setUpdatedAt(new DateTimeImmutable());
        $this->menuRepository->persist($devis);
        $this->menuRepository->flush();
        $di = new Menu();
        $di->setActive(1);
        $di->setRoute('di_list');
        $di->setParentId($projet->getId());
        $di->setName('Suivi des DI');
        $di->setPosition(2);
        $di->setSlug('suivi-des-di');
        $di->setCreatedAt(new DateTimeImmutable());
        $di->setUpdatedAt(new DateTimeImmutable());
        $this->menuRepository->persist($di);
        $this->menuRepository->flush();
        $orders = new Menu();
        $orders->setActive(1);
        $orders->setRoute('orders');
        $orders->setParentId($projet->getId());
        $orders->setName('Commande client');
        $orders->setPosition(3);
        $orders->setSlug('commande-client');
        $orders->setCreatedAt(new DateTimeImmutable());
        $orders->setUpdatedAt(new DateTimeImmutable());
        $this->menuRepository->persist($orders);
        $this->menuRepository->flush();
        $invoices = new Menu();
        $invoices->setActive(1);
        $invoices->setRoute('invoices');
        $invoices->setParentId($projet->getId());
        $invoices->setName('Facture client');
        $invoices->setPosition(4);
        $invoices->setSlug('facture-client');
        $invoices->setCreatedAt(new DateTimeImmutable());
        $invoices->setUpdatedAt(new DateTimeImmutable());
        $this->menuRepository->persist($invoices);
        $this->menuRepository->flush();
        $images = new Menu();
        $images->setActive(1);
        $images->setRoute('images');
        $images->setParentId($tapis->getId());
        $images->setName('Image commande');
        $images->setPosition(0);
        $images->setSlug('images');
        $images->setCreatedAt(new DateTimeImmutable());
        $images->setUpdatedAt(new DateTimeImmutable());
        $this->menuRepository->persist($images);
        $this->menuRepository->flush();
        $supplierInvoice = new Menu();
        $supplierInvoice->setActive(1);
        $supplierInvoice->setRoute('supplier_invoices');
        $supplierInvoice->setParentId($tapis->getId());
        $supplierInvoice->setName('Factures fournisseur');
        $supplierInvoice->setPosition(2);
        $supplierInvoice->setSlug('supplier-invoices');
        $supplierInvoice->setCreatedAt(new DateTimeImmutable());
        $supplierInvoice->setUpdatedAt(new DateTimeImmutable());
        $this->menuRepository->persist($supplierInvoice);
        $this->menuRepository->flush();

        /*add Workshop*/
        $workShop = new Menu();
        $workShop->setActive(1);
        $workShop->setRoute('work_shop');
        $workShop->setParentId($tapis->getId());
        $workShop->setName('Tapis');
        $workShop->setPosition(4);
        $workShop->setSlug('work-shop');
        $workShop->setCreatedAt(new DateTimeImmutable());
        $workShop->setUpdatedAt(new DateTimeImmutable());
        $this->menuRepository->persist($workShop);
        $this->menuRepository->flush();

        /*add checkingList*/
        $checkingList = new Menu();
        $checkingList->setActive(1);
        $checkingList->setRoute('checking_list');
        $checkingList->setParentId($tapis->getId());
        $checkingList->setName('Checking Lists');
        $checkingList->setPosition(5);
        $checkingList->setSlug('checking-list');
        $checkingList->setCreatedAt(new DateTimeImmutable());
        $checkingList->setUpdatedAt(new DateTimeImmutable());
        $this->menuRepository->persist($checkingList);
        $this->menuRepository->flush();

        /*add progressReport */
        $progressReport = new Menu();
        $progressReport->setActive(1);
        $progressReport->setRoute('progress_report');
        $progressReport->setParentId($tapis->getId());
        $progressReport->setName('Progress Reports');
        $progressReport->setPosition(6);
        $progressReport->setSlug('progress-report');
        $progressReport->setCreatedAt(new DateTimeImmutable());
        $progressReport->setUpdatedAt(new DateTimeImmutable());
        $this->menuRepository->persist($progressReport);
        $this->menuRepository->flush();

        /*add Settings menu*/
        $settings = new Menu();
        $settings->setActive(1);
        $settings->setRoute('#');
        $settings->setName('Paramètres');
        $settings->setPosition(4);
        $settings->setSlug('settings');
        $settings->setParentId(0);
        $settings->setCreatedAt(new DateTimeImmutable());
        $settings->setUpdatedAt(new DateTimeImmutable());
        $this->menuRepository->persist($settings);
        $this->menuRepository->flush();

        $collectionsProduits = new Menu();
        $collectionsProduits->setActive(1);
        $collectionsProduits->setRoute('collections-produits');
        $collectionsProduits->setParentId($settings->getId());
        $collectionsProduits->setName('Collections & Produits');
        $collectionsProduits->setPosition(0);
        $collectionsProduits->setSlug('collections-produits');
        $collectionsProduits->setCreatedAt(new DateTimeImmutable());
        $collectionsProduits->setUpdatedAt(new DateTimeImmutable());
        $this->menuRepository->persist($collectionsProduits);
        $this->menuRepository->flush();

        $transportLivraison = new Menu();
        $transportLivraison->setActive(1);
        $transportLivraison->setRoute('transport-livraison');
        $transportLivraison->setParentId($settings->getId());
        $transportLivraison->setName('Transport & Livraison');
        $transportLivraison->setPosition(1);
        $transportLivraison->setSlug('transport-livraison');
        $transportLivraison->setCreatedAt(new DateTimeImmutable());
        $transportLivraison->setUpdatedAt(new DateTimeImmutable());
        $this->menuRepository->persist($transportLivraison);
        $this->menuRepository->flush();

        $couleursMateriaux = new Menu();
        $couleursMateriaux->setActive(1);
        $couleursMateriaux->setRoute('couleurs-materiaux');
        $couleursMateriaux->setParentId($settings->getId());
        $couleursMateriaux->setName('Couleurs & Matériaux');
        $couleursMateriaux->setPosition(2);
        $couleursMateriaux->setSlug('couleurs-materiaux');
        $couleursMateriaux->setCreatedAt(new DateTimeImmutable());
        $couleursMateriaux->setUpdatedAt(new DateTimeImmutable());
        $this->menuRepository->persist($couleursMateriaux);
        $this->menuRepository->flush();

        $fabricantsQualite = new Menu();
        $fabricantsQualite->setActive(1);
        $fabricantsQualite->setRoute('fabricants-qualite');
        $fabricantsQualite->setParentId($settings->getId());
        $fabricantsQualite->setName('Fabricants & Qualité');
        $fabricantsQualite->setPosition(3);
        $fabricantsQualite->setSlug('fabricants-qualite');
        $fabricantsQualite->setCreatedAt(new DateTimeImmutable());
        $fabricantsQualite->setUpdatedAt(new DateTimeImmutable());
        $this->menuRepository->persist($fabricantsQualite);
        $this->menuRepository->flush();

        $tarificationTaxes = new Menu();
        $tarificationTaxes->setActive(1);
        $tarificationTaxes->setRoute('tarification-taxes');
        $tarificationTaxes->setParentId($settings->getId());
        $tarificationTaxes->setName('Tarification & Taxes');
        $tarificationTaxes->setPosition(4);
        $tarificationTaxes->setSlug('tarification-taxes');
        $tarificationTaxes->setCreatedAt(new DateTimeImmutable());
        $tarificationTaxes->setUpdatedAt(new DateTimeImmutable());
        $this->menuRepository->persist($tarificationTaxes);
        $this->menuRepository->flush();

        $paymentTypes = new Menu();
        $paymentTypes->setActive(1);
        $paymentTypes->setRoute('payment-types');
        $paymentTypes->setParentId($settings->getId());
        $paymentTypes->setName('Types de paiement');
        $paymentTypes->setPosition(5);
        $paymentTypes->setSlug('payment-types');
        $paymentTypes->setCreatedAt(new DateTimeImmutable());
        $paymentTypes->setUpdatedAt(new DateTimeImmutable());
        $this->menuRepository->persist($paymentTypes);
        $this->menuRepository->flush();

        $formesTraitements = new Menu();
        $formesTraitements->setActive(1);
        $formesTraitements->setRoute('formes-traitements');
        $formesTraitements->setParentId($settings->getId());
        $formesTraitements->setName('Formes & Traitements');
        $formesTraitements->setPosition(6);
        $formesTraitements->setSlug('formes-traitements');
        $formesTraitements->setCreatedAt(new DateTimeImmutable());
        $formesTraitements->setUpdatedAt(new DateTimeImmutable());
        $this->menuRepository->persist($formesTraitements);
        $this->menuRepository->flush();

        $imagesModels = new Menu();
        $imagesModels->setActive(1);
        $imagesModels->setRoute('images-models');
        $imagesModels->setParentId($settings->getId());
        $imagesModels->setName('Images & Modèles');
        $imagesModels->setPosition(7);
        $imagesModels->setSlug('images-models');
        $imagesModels->setCreatedAt(new DateTimeImmutable());
        $imagesModels->setUpdatedAt(new DateTimeImmutable());
        $this->menuRepository->persist($imagesModels);
        $this->menuRepository->flush();

        $attachmentTypes = new Menu();
        $attachmentTypes->setActive(1);
        $attachmentTypes->setRoute('attachment-types');
        $attachmentTypes->setParentId($settings->getId());
        $attachmentTypes->setName('Types de pièces jointes');
        $attachmentTypes->setPosition(8);
        $attachmentTypes->setSlug('attachment-types');
        $attachmentTypes->setCreatedAt(new DateTimeImmutable());
        $attachmentTypes->setUpdatedAt(new DateTimeImmutable());
        $this->menuRepository->persist($attachmentTypes);
        $this->menuRepository->flush();

        /*add Reglement menu*/
        $reglement = new Menu();
        $reglement->setActive(1);
        $reglement->setRoute('reglement');
        $reglement->setParentId($tresorerie->getId());
        $reglement->setName('Règlements');
        $reglement->setPosition(0);
        $reglement->setSlug('reglement');
        $reglement->setCreatedAt(new DateTimeImmutable());
        $reglement->setUpdatedAt(new DateTimeImmutable());
        $this->menuRepository->persist($reglement);
        $this->menuRepository->flush();

        $contactPermissions = $this->permissionRepository->findBy(['entity' => 'Contact']);

        if (count($contactPermissions)) {
            foreach ($contactPermissions as $contactPermission) {
                $contacts->addPermission($contactPermission);
                $listContacts->addPermission($contactPermission);
                $this->menuRepository->persist($contacts);
                $this->menuRepository->persist($listContacts);
            }
        }
        $contremarquePermissions = $this->permissionRepository->findBy(['entity' => 'Contremarque']);
        if (count($contremarquePermissions)) {
            foreach ($contremarquePermissions as $contremarquePermission) {
                $contremarque->addPermission($contremarquePermission);
                $this->menuRepository->persist($contremarque);
            }
        }
        $devisPermissions = $this->permissionRepository->findBy(['entity' => 'Quote']);
        if (count($devisPermissions)) {
            foreach ($devisPermissions as $devisPermission) {
                $devis->addPermission($devisPermission);
                $this->menuRepository->persist($devis);
            }
        }
        $diPermissions = $this->permissionRepository->findBy(['entity' => 'DI']);
        if (count($diPermissions)) {
            foreach ($diPermissions as $diPermission) {
                $di->addPermission($diPermission);
                $this->menuRepository->persist($di);
            }
        }
        $ordersPermissions = $this->permissionRepository->findBy(['entity' => 'Order']);
        if (count($ordersPermissions)) {
            foreach ($ordersPermissions as $ordersPermission) {
                $orders->addPermission($ordersPermission);
                $this->menuRepository->persist($orders);
            }
        }
        $invoicesPermissions = $this->permissionRepository->findBy(['entity' => 'Invoice']);
        if (count($invoicesPermissions)) {
            foreach ($invoicesPermissions as $invoicesPermission) {
                $invoices->addPermission($invoicesPermission);
                $this->menuRepository->persist($invoices);
            }
        }

        // project menu
        if (count($contremarquePermissions)) {
            foreach ($contremarquePermissions as $contremarquePermission) {
                $projet->addPermission($contremarquePermission);
                $this->menuRepository->persist($projet);
            }
        }
        if (count($devisPermissions)) {
            foreach ($devisPermissions as $devisPermission) {
                $projet->addPermission($devisPermission);
                $this->menuRepository->persist($projet);
            }
        }
        if (count($diPermissions)) {
            foreach ($diPermissions as $diPermission) {
                $projet->addPermission($diPermission);
                $this->menuRepository->persist($projet);
            }
        }

        if (count($ordersPermissions)) {
            foreach ($ordersPermissions as $ordersPermission) {
                $projet->addPermission($ordersPermission);
                $this->menuRepository->persist($projet);
            }
        }

        if (count($invoicesPermissions)) {
            foreach ($invoicesPermissions as $invoicesPermission) {
                $projet->addPermission($invoicesPermission);
                $this->menuRepository->persist($projet);
            }
        }

        // tapis menu
        $imagesPermissions = $this->permissionRepository->findBy(['entity' => 'Image']);
        if (count($imagesPermissions)) {
            foreach ($imagesPermissions as $imagesPermission) {
                $images->addPermission($imagesPermission);
                $this->menuRepository->persist($images);
            }
        }
        $tapisPermissions = $this->permissionRepository->findBy(['entity' => 'Carpet']);

        if (count($invoicesPermissions)) {
            foreach ($invoicesPermissions as $invoicesPermission) {
                $supplierInvoice->addPermission($invoicesPermission);
                $this->menuRepository->persist($supplierInvoice);
            }
        }

        if (count($imagesPermissions)) {
            foreach ($imagesPermissions as $imagesPermission) {
                $tapis->addPermission($imagesPermission);
                $this->menuRepository->persist($tapis);
            }
        }
        if (count($tapisPermissions)) {
            foreach ($tapisPermissions as $tapisPermission) {
                $tapis->addPermission($tapisPermission);
                $this->menuRepository->persist($tapis);
            }
        }
        if (count($invoicesPermissions)) {
            foreach ($invoicesPermissions as $invoicesPermission) {
                $tapis->addPermission($invoicesPermission);
                $this->menuRepository->persist($tapis);
            }
        }

        $treasuryPermissions = $this->permissionRepository->findBy(['entity' => 'Treasury']);
        if (count($treasuryPermissions)) {
            foreach ($treasuryPermissions as $treasuryPermission) {
                $tresorerie->addPermission($treasuryPermission);
                $this->menuRepository->persist($tresorerie);
            }
        }

        // Add permissions for Settings menu items
        $settingPermissions = $this->permissionRepository->findBy(['entity' => 'Setting']);
        if (count($settingPermissions)) {
            foreach ($settingPermissions as $settingPermission) {
                $settings->addPermission($settingPermission);
                $collectionsProduits->addPermission($settingPermission);
                $transportLivraison->addPermission($settingPermission);
                $couleursMateriaux->addPermission($settingPermission);
                $fabricantsQualite->addPermission($settingPermission);
                $tarificationTaxes->addPermission($settingPermission);
                $paymentTypes->addPermission($settingPermission);
                $formesTraitements->addPermission($settingPermission);
                $imagesModels->addPermission($settingPermission);
                $attachmentTypes->addPermission($settingPermission);
                $this->menuRepository->persist($settings);
                $this->menuRepository->persist($collectionsProduits);
                $this->menuRepository->persist($transportLivraison);
                $this->menuRepository->persist($couleursMateriaux);
                $this->menuRepository->persist($fabricantsQualite);
                $this->menuRepository->persist($tarificationTaxes);
                $this->menuRepository->persist($paymentTypes);
                $this->menuRepository->persist($formesTraitements);
                $this->menuRepository->persist($imagesModels);
                $this->menuRepository->persist($attachmentTypes);
            }
        }

        // Add Treasury permissions to Reglement
        if (count($treasuryPermissions)) {
            foreach ($treasuryPermissions as $treasuryPermission) {
                $reglement->addPermission($treasuryPermission);
                $this->menuRepository->persist($reglement);
            }
        }

        // Add Workshop permissions
        $workshopPermissions = $this->permissionRepository->findBy(['entity' => 'Workshop']);
        if (count($workshopPermissions)) {
            foreach ($workshopPermissions as $workshopPermission) {
                $workShop->addPermission($workshopPermission);
                $this->menuRepository->persist($workShop);
            }
        }

        // Add Workshop permissions to parent Tapis menu
        if (count($workshopPermissions)) {
            foreach ($workshopPermissions as $workshopPermission) {
                $tapis->addPermission($workshopPermission);
                $this->menuRepository->persist($tapis);
            }
        }

        $this->menuRepository->flush();

        return 0;
    }
}
