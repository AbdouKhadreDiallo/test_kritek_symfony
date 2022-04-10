<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Entity\Invoicelines;
use App\Form\InvoiceFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AppController extends AbstractController
{
    /**
     * @Route("/", name="app_app")
     */
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        $manager = $doctrine->getManager();
        
        
        $invoce = new Facture();
        $line = new Invoicelines();
        $invoce->getInvoicelines()->add($line);
        $form = $this->createForm(InvoiceFormType::class, $invoce);
        $tva = 0.18; // 18/100
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $items = $invoce->getInvoicelines();
            $lastRecord =  $manager->getRepository(Facture::class)->findOneBy([], ['id' => 'desc']);
            $invoce->setInvoiceNumber($lastRecord ?  (int)$lastRecord->getId() + 1 : 1);
            $manager->persist($invoce);
            foreach($items as $item){
                $dutyFree = $item->getQuantity() * $item->getAmount();
                $allTaxeIncluded = $dutyFree * 0.18;
                $total = $allTaxeIncluded + $dutyFree;
                $item->setTotal($total);
                $item->setVatAmount($allTaxeIncluded);
                $item->setInvoice($invoce);
                $manager->persist($item);
            }
            $manager->flush();
            unset($form);
            unset($invoce);
            unset($line);
            $invoce = new Facture();
            $line = new Invoicelines();
            $invoce->getInvoicelines()->add($line);
            $form = $this->createForm(InvoiceFormType::class, $invoce);
        }

        return $this->render('app/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
