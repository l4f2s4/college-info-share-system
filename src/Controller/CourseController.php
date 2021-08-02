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
class CourseController extends AbstractController
{
    /**
     * @Route("/course", name="course")
     */
    public function index(Request $request): Response
    {

    $user1=$this->getUser();
        if($user1 != null )
        {
            $usertest=$user1->getId();
            $em=$this->getDoctrine()->getManager();
            $connection=$em->getConnection();
            $findusertest=$connection->prepare("select id,coursename,credits,cshortname from course inner join course_user on id=course_id where user_id=:usertest");
            $findusertest->bindParam(':usertest',$usertest);
            $findusertest->execute();
            $findusertest=$findusertest->fetchAll();
            return $this->render('course/index.html.twig',['findusertest'=>$findusertest]);
        }
        else
        {
            $success="You must sign in again to access you account";
            return $this->render('college/404.html.twig',['success'=>$success]);
        }
    }
       /**
     * @Route("/course/addinstructor/{id}/{programme}", name="instructor")
     */
    public function instructor(Request $request,$id,$programme): Response
    {

    $user1=$this->getUser();
   if($user1 != null )
        {

            $pprogramme =$this->getDoctrine()->getRepository(Programme::class)->findAll();
            $findCname =$this->getDoctrine()->getRepository(Course::class)->findAll();
            $em=$this->getDoctrine()->getManager();
            $connection=$em->getConnection();
            $statement6=$connection->prepare("select count(*) from course");
            $statement6->execute();
            $coursere=$statement6->fetchOne();
            $findcourse=$connection->prepare(" select user.email,user.name as Name,user.userimage as APP from user inner join course_user on course_id='".$id."' and user.id=user_id;");
            $findcourse->execute();
            $findcourse=$findcourse->fetchAll();
            $findInstructor=$connection->prepare(" select user.email,user.name as Name,user.userimage as APP from user inner join course_user on course_id='".$id."' and user.id=user_id;");
            $findInstructor->execute();
            $findInstructor=$findInstructor->fetchAll();
            $Course =new Course();
            $project=$request->request->get('project');
             if($request->isMethod('post'))
             {
                  if(!empty($project))
                    {
                      $projectname =$this->getDoctrine()->getRepository(User::class)->findOneBy(['email'=>$project]);
                       if($projectname)
                           {

                                             $projection =$this->getDoctrine()->getRepository(Course::class)->findOneBy(['id' => $id]);
                                             $projection->addInstructor($projectname);
                                             $em->persist($projection);
                                             $em->flush();
                                            return $this->redirect($this->generateUrl('instructor',['id'=>$id,'programme'=>$programme]));
                           }
                           else
                           {
                                  $this->addFlash('addme','User email not found!..');
                             return $this->render('programme/addinstructor.html.twig',['findInstructor'=>$findInstructor,'findcourse'=>$findcourse,'findCname'=>$findCname,'coursere'=>$coursere,'pprogramme'=>$pprogramme,'programme'=>$programme]);
                           }
                    }
                    else
                     {
                          $this->addFlash('addme',  'This field is required!..');
                         return $this->render('programme/addinstructor.html.twig',['findInstructor'=>$findInstructor,'findcourse'=>$findcourse,'findCname'=>$findCname,'coursere'=>$coursere,'pprogramme'=>$pprogramme,'programme'=>$programme]);
                     }
             }
           return $this->render('programme/addinstructor.html.twig',['findInstructor'=>$findInstructor,'findcourse'=>$findcourse,'findCname'=>$findCname,'coursere'=>$coursere,'pprogramme'=>$pprogramme,'programme'=>$programme]);
        }
        else
        {
                $success="You must sign in again to access you account";
                return $this->render('college/404.html.twig',['success'=>$success]);
        }
    }
         /**
     * @Route("/{id}/edit",name="courseedit")
     */
public function cedit(Request $request,$id)
{
       $entityManager = $this->getDoctrine()->getManager();
        $Course=new Course();
        $course_name =$this->getDoctrine()->getRepository(Course::class)->findOneBy(['id' => $id]);
        $connection=$entityManager->getConnection();
        $statement6=$connection->prepare("select name from programme inner join course_programme on course_id='".$id."'");
        $statement6->execute();
        $programme=$statement6->fetchOne();
        $statement7=$connection->prepare("select id from programme inner join course_programme on course_id='".$id."'");
        $statement7->execute();
        $idea=$statement7->fetchOne();

        $form = $this->createFormBuilder($course_name)

        ->add('coursename',TextType::class, array('label'=>'Course Name','attr' => array('class' => 'form-control')))
        ->add('cshortname',TextType::class, array('label'=>'Course Name','attr' => array('class' => 'form-control')))
        ->add('credits',TextType::class, array('label'=>'Course Name','attr' => array('class' => 'form-control')))
        ->add('save',SubmitType::class, array(
            'label' => 'save',
            'attr' => array('class' => 'btn btn-primary submit-btn')
        ))
        ->getForm();

        $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager->flush();
             return $this->redirect($this->generateUrl('area',['id'=>$idea,'programme'=>$programme]));
        }
        return $this->render('course/cedit.html.twig',array('form'=>$form->createView()

                ));
}
       /**
     * @Route("/{id}/remove/course",name="courseremove")
     */
public function cremove(Request $request,$id)
{
        $course_name =$this->getDoctrine()->getRepository(Course::class)->findOneBy(['id' => $id]);
        $entityManager = $this->getDoctrine()->getManager();
        $connection=$entityManager->getConnection();
        $statement6=$connection->prepare("select name from programme inner join course_programme on course_id='".$id."'");
        $statement6->execute();
        $programme=$statement6->fetchOne();
        $statement7=$connection->prepare("select id from programme inner join course_programme on course_id='".$id."'");
        $statement7->execute();
        $idea=$statement7->fetchOne();;
        $entityManager->remove($course_name);
        $entityManager->flush();
          return $this->redirect($this->generateUrl('area',['id'=>$idea,'programme'=>$programme]));
}

     /**
     * @Route("/course/addstudent/anon/{id}/{course}", name="student")
     */
    public function rapper(Request $request,$id,$course): Response
    {

    $user1=$this->getUser();
   if($user1 != null )
        {
            $findCname =$this->getDoctrine()->getRepository(Course::class)->findAll();
            $em=$this->getDoctrine()->getManager();
            $connection=$em->getConnection();
            $statement6=$connection->prepare("select count(*) from course");
            $statement6->execute();
            $coursere=$statement6->fetchOne();
            $findcourse=$connection->prepare("select * from programme inner join course_programme on id=programme_id where course_id='".$id."'");
            $findcourse->execute();
            $findcourse=$findcourse->fetchAll();
            $Course =new Course();
            $project=$request->request->get('project');
             if($request->isMethod('post'))
             {
                  if(!empty($project))
                    {
                      $projectname =$this->getDoctrine()->getRepository(Student::class)->findOneBy(['regno'=>$project]);
                       if($projectname)
                           {

                                             $projection =$this->getDoctrine()->getRepository(Course::class)->findOneBy(['id' => $id]);
                                             $projection->addCoursestudent($projectname);
                                             $em->persist($projection);
                                             $em->flush();
                                            return $this->redirect($this->generateUrl('student',['id'=>$id,'course'=>$course]));
                           }
                           else
                           {
                                  $this->addFlash('addme','Student not found!..');
                             return $this->render('course/addstudent.html.twig',['findcourse'=>$findcourse,'findCname'=>$findCname,'course'=>$course]);
                           }
                    }
                    else
                     {
                          $this->addFlash('addme',  'This field is required!..');
                        return $this->render('course/addstudent.html.twig',['findcourse'=>$findcourse,'findCname'=>$findCname,'course'=>$course]);
                     }
             }
          return $this->render('course/addstudent.html.twig',['findcourse'=>$findcourse,'findCname'=>$findCname,'course'=>$course]);
        }
        else
        {
                $success="You must sign in again to access you account";
                return $this->render('college/404.html.twig',['success'=>$success]);
        }
    }


}
