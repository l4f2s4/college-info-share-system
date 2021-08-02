<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\Department;
use App\Entity\User;
use App\Entity\Student;
use App\Entity\Project;
use App\Entity\Course;
use App\Entity\Programme;
class ProgrammeController extends AbstractController
{
    /**
     * @Route("/programme", name="programme")
     */
    public function index(Request $request): Response
    {
       $user1=$this->getUser();
        if($user1 != null )
        {
           $title=$user1->getTitle();
           $project=$user1->getId();
           $finddept =$this->getDoctrine()->getRepository(Department::class)->findAll();
            $em=$this->getDoctrine()->getManager();
            $connection=$em->getConnection();
            $statement6=$connection->prepare("select count(*) from programme");
            $statement6->execute();
            $prog=$statement6->fetchOne();
            $statement7=$connection->prepare(" select department.id as idr,department.name as DNAME,programme.name as PNAME,programme.id as ID,programme.pshortname as short from programme inner join department on programmedept_id=department.id");
            $statement7->execute();
            $statement10=$connection->prepare("select department_id from user where id=:project");
            $statement10->bindParam(':project',$project);
            $statement10->execute();
            $findprogramme=$statement7->fetchAll();
            $dept=$statement10->fetchOne();
            $statement611=$connection->prepare("select count(*) from programme where programmedept_id='".$dept."'");
            $statement611->execute();
            $progrec=$statement611->fetchOne();
            $programme =new Programme();
            $pname=$request->request->get('pname');
            $psname=$request->request->get('psname');
            $deptname=$request->request->get('deptname');
             $findl =$this->getDoctrine()->getRepository(Department::class)->findOneBy(['name'=>$deptname]);
              $findlove =$this->getDoctrine()->getRepository(Department::class)->findOneBy(['id'=>$dept]);
            if($request->isMethod('post'))
            {
                if($title=='administrator')
                {
                      if(!empty($pname) & !empty($psname) &!empty($deptname))
                       {
                            $programme->setName($pname);
                            $programme->setPshortname($psname);
                            $programme->setProgrammedept($findl);
                            $em->persist($programme);
                            $em->flush();
                            return $this->redirectToRoute('programme');
                        }
                       else
                        {
                         $this->addFlash('danger',  'This field are required!..');
                         return $this->render('programme/index.html.twig',['dept'=>$dept,'prog'=>$prog,'progrec'=>$progrec,'findprogramme'=>$findprogramme,'finddept'=>$finddept]);
                        }
                }
            else{
                         if(!empty($pname) & !empty($psname))
                       {
                            $programme->setName($pname);
                            $programme->setPshortname($psname);
                            $programme->setProgrammedept($findlove);
                            $em->persist($programme);
                            $em->flush();
                            return $this->redirectToRoute('programme');
                        }
                       else
                        {
                         $this->addFlash('danger',  'This field are required!..');
                         return $this->render('programme/index.html.twig',['dept'=>$dept,'prog'=>$prog,'progrec'=>$progrec,'findprogramme'=>$findprogramme,'finddept'=>$finddept]);
                        }

                }
                          $this->addFlash('danger',  '');
                          return $this->render('programme/index.html.twig',['dept'=>$dept,'prog'=>$prog,'progrec'=>$progrec,'findprogramme'=>$findprogramme,'finddept'=>$finddept]);
                     }
                          $this->addFlash('danger',  '');
                         return $this->render('programme/index.html.twig',['dept'=>$dept,'prog'=>$prog,'progrec'=>$progrec,'findprogramme'=>$findprogramme,'finddept'=>$finddept]);
         }
        else
        {
            $success="You must sign in again to access you account";
            return $this->render('college/404.html.twig',['success'=>$success]);
        }
    }

           /**
     * @Route("/{id}/edit/programme",name="programmeedit")
     */
public function pedit(Request $request,$id)
{
        $programme_name =$this->getDoctrine()->getRepository(Programme::class)->findOneBy(['id' => $id]);
        $form = $this->createFormBuilder($programme_name)

        ->add('name',TextType::class, array('label'=>'Programme Name','attr' => array('class' => 'form-control')))
        ->add('pshortname',TextType::class, array('label'=>'Programme Short Name','attr' => array('class' => 'form-control')))

        ->add('save',SubmitType::class, array(
            'label' => 'save',
            'attr' => array('class' => 'btn btn-primary submit-btn')
        ))
        ->getForm();

        $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirect($this->generateUrl('programme'));
        }
        return $this->render('programme/pedit.html.twig',array('form'=>$form->createView()

                ));
}
       /**
     * @Route("/{id}/remove/available",name="programmeremove")
     */
public function premove(Request $request,$id)
{
        $programme_name =$this->getDoctrine()->getRepository(Programme::class)->findOneBy(['id' => $id]);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($programme_name);
        $entityManager->flush();
        return $this->redirect($this->generateUrl('programme'));
}
     /**
     * @Route("/{programme}/{id}/Area", name="area")
     */
public function addArea(Request $request,$id,$programme): Response
{
    $user1=$this->getUser();
        if($user1 != null )
        {

           $findcourse =$this->getDoctrine()->getRepository(Course::class)->findAll();
           $findCname =$this->getDoctrine()->getRepository(Course::class)->findAll();
           $pprogramme =$this->getDoctrine()->getRepository(Programme::class)->findAll();

            $em=$this->getDoctrine()->getManager();
            $connection=$em->getConnection();
            $findcourse=$connection->prepare("select id,coursename,cshortname,credits from course innner join course_programme on id=course_id where programme_id=:id");
            $findcourse->bindParam(':id',$id);
            $findcourse->execute();
            $findcourse=$findcourse->fetchAll();

            $statement6=$connection->prepare("select count(*) from course");
            $statement6->execute();
            $coursere=$statement6->fetchOne();
            $findInstructor=$connection->prepare("select course.coursename as Cname,course.id as Cid,user.email,user.name as Name,user.userimage as APP from user inner join course_user inner join course on course_id=course.id where user_id=user.id");
            $findInstructor->execute();
            $findInstructor=$findInstructor->fetchAll();
            $Course =new Course();
            $cname=$request->request->get('cname');
            $credit=$request->request->get('credit');
            $csname=$request->request->get('csname');

            if($request->isMethod('post'))
            {
              if(!empty($cname) & !empty($credit)& !empty($csname))
               {
                   $pp =$this->getDoctrine()->getRepository(Programme::class)->findOneBy(['id'=>$id]);
                    $Course->setCoursename($cname);
                    $Course->setCredits($credit);
                    $Course->setCshortname($csname);
                    $Course->addProgrammecourse($pp);
                    $em->persist($Course);
                    $em->flush();
                   return $this->redirect($this->generateUrl('area',['id'=>$id,'programme'=>$programme]));
                }
               else
                {
                 $this->addFlash('danger',  'This field is required!..');
                 return $this->render('programme/assigndept.html.twig',['findInstructor'=>$findInstructor,'findcourse'=>$findcourse,'findCname'=>$findCname,'coursere'=>$coursere,'pprogramme'=>$pprogramme,'programme'=>$programme]);
                }
                  $this->addFlash('danger',  '');
                  return $this->render('programme/assigndept.html.twig',['findInstructor'=>$findInstructor,'findcourse'=>$findcourse,'findCname'=>$findCname,'coursere'=>$coursere,'pprogramme'=>$pprogramme,'programme'=>$programme]);
             }
                  $this->addFlash('danger',  '');
                 return $this->render('programme/assigndept.html.twig',['findInstructor'=>$findInstructor,'findcourse'=>$findcourse,'findCname'=>$findCname,'coursere'=>$coursere,'pprogramme'=>$pprogramme,'programme'=>$programme]);
        }
        else
        {
            $success="You must sign in again to access you account";
            return $this->render('college/404.html.twig',['success'=>$success]);
        }
 }
}
