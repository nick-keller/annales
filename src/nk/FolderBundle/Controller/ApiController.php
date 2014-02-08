<?php

namespace nk\FolderBundle\Controller;

use nk\FolderBundle\Entity\Folder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Request
     */
    private $request;

    /**
     * @Secure(roles="ROLE_USER")
     */
    public function renameAction(Folder $folder)
    {
        if($folder->getAuthor() != $this->getUser())
            return $this->getResponse(array(
                'success' => 0,
                'error' => "Vous devez être propriétaire de ce dossier",
            ));

        $folder->setName($this->request->query->get('name'));
        $this->em->persist($folder);
        $this->em->flush();

        return $this->getResponse(array('success' => 1));
    }

    /**
     * @Secure(roles="ROLE_USER")
     */
    public function newAction()
    {
        $folder = new Folder;
        if(($name = $this->request->query->get('name', null)) !== null)
            $folder->setName($name);
        $this->em->persist($folder);
        $this->em->flush();

        $response = array(
            'success' => 1,
            'id' => $folder->getId(),
            'name' => $folder->getName(),
            'rename' => $this->generateUrl('nk_folder_rename', array('id' => $folder->getId())),
            'link' => $this->generateUrl('nk_folder_show', array('id' => $folder->getId())),
            'addDoc' => str_replace('777', '__id__', $this->generateUrl('nk_folder_add', array('id' => $folder->getId(), 'docId' => 777))),
        );

        return $this->getResponse($response);
    }

    /**
     * @Secure(roles="ROLE_USER")
     */
    public function addAction(Folder $folder, $docId)
    {
        if($folder->getAuthor() != $this->getUser())
            return $this->getResponse(array(
                'success' => 0,
                'error' => "Vous devez être propriétaire de ce dossier",
            ));

        $document = $this->em->getRepository('nkDocumentBundle:Document')->findOneById($docId);
        $folder->addDocument($document);

        $this->em->persist($folder);
        $this->em->flush();

        return $this->getResponse(array('success' => 1));
    }

    public function downloadAction(Folder $folder)
    {
        if(count($folder->getDocuments()) === 0)
            return $this->redirect($this->generateUrl('nk_folder_show', array(
                'id' => $folder->getId(),
            )));

        if(count($folder->getDocuments()) === 1)
            return $this->forward('nkDocumentBundle:Document:download', array(
                'document'  => $folder->getDocuments()[0],
            ));

        $file = $this->get('nk.zip_factory')->createMultiple($folder->getDocuments());

        $response = new Response();
        $response->headers->set('Content-Type', "application/zip");
        $response->headers->set('Content-Disposition', 'attachment; filename="'.$folder->getSlug().'.zip"');
        $response->headers->set('Content-Transfer-Encoding', 'binary');
        $response->headers->set('Content-Length', filesize($file));
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');
        $response->setStatusCode(200);
        $response->setContent(file_get_contents($file));

        $this->get('nk.zip_factory')->remove($file);

        return $response;
    }

    /**
     * @Secure(roles="ROLE_USER")
     */
    public function addToCollectionAction(Folder $folder)
    {
        if($folder->getAuthor() == $this->getUser())
            return $this->getResponse(array(
                'success' => 0,
                'error' => "Impossible d'ajouter une compil dont on est l'auteur",
            ));

        $folder->addUser($this->getUser());
        $this->em->persist($folder);
        $this->em->flush();

        return $this->getResponse(array('success' => 1));
    }

    /**
     * @Secure(roles="ROLE_USER")
     */
    public function copyAction(Folder $f)
    {
        $folder = new Folder();
        $folder->setName($f->getName());
        $folder->setAuthor($this->getUser());

        foreach($f->getDocuments() as $document){
            $folder->addDocument($document);
        }

        $this->em->persist($folder);
        $this->em->flush();

        return $this->getResponse(array('success' => 1));
    }

    private function getResponse(array $tab)
    {
        $response = new Response(json_encode( $tab ));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
