<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\Department;
use App\Entity\User;
use App\Entity\Promotion;
use App\Entity\Student;
use App\Entity\Project;

class RemoveController extends AbstractController
{
    /**
     * @Route("/remove/{lafesa}/{projectname}/{id}", name="removeprojectuser")
     */
    public function just(Request $request,$id,$lafesa,$projectname): Response
    {
            $user1=$this->getUser();
            $em=$this->getDoctrine()->getManager();
            $connection=$em->getConnection();
            if($user1!=null){
                $em=$this->getDoctrine()->getManager();
                $finddept =$this->getDoctrine()->getRepository(Department::class)->findAll();
                $connection=$em->getConnection();
                $statement7=$connection->prepare("select project_id as lafesa,user.id as ID,user.name as username,user.email as email from user inner join project_user where user_id=user.id and project_id=:lafesa");
                $statement7->bindParam(':lafesa',$lafesa);
                $statement7->execute();
                $display=$statement7->fetchAll();
                $jina =$this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $id]);
                $project=$this->getDoctrine()->getRepository(Project::class)->findOneBy(['id' => $lafesa]);
                if($jina){
                    $project->removeAdduser($jina);
                    $em->persist($project);
                    $em->flush();
                  return $this->redirect($this->generateUrl('adduser',['id'=>$lafesa,'proj'=>$projectname]));
                }
                else{
                    return $this->render( 'security/assignuser.html.twig',[
                    'controller_name' => 'RemoveController','finddept'=>$finddept,'display'=>$display,'projectname'=>$projectname,
                ]);
                }
                }
            else{
                      $success="Permission revoke..please contact your administrator for more info";
                      return $this->render('college/accessdenied.html.twig',['success'=>$success]);
            }
    }
     /**
     * @Route("/remove/{id}", name="removeuser")
     */
    public function removeuser(Request $request,$id): Response
    {
            $user1=$this->getUser();
            $em=$this->getDoctrine()->getManager();
            $connection=$em->getConnection();
            if($user1!=null){
                    $userid=$this->getUser()->getId();
                    $projectuser =$this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $id]);
                    $finddept =$this->getDoctrine()->getRepository(Department::class)->findAll();
                    $statement3=$connection->prepare("select user.id as ID,department.name as department,user.name,user.email,user.phoneno,user.title,user.do_b,user.do_m,user.employeestatus,deptcom,user.gender from user inner join department on user.department_id=department.id");
                    $statement3->execute();
                    $displaystaff=$statement3->fetchAll();
            if($projectuser){
                    $em->remove($projectuser);
                    $em->flush();
                    return $this->redirectToRoute('head');
            }
            else if($userid==$id){
                    $session = $this->get('session');
                    $session = new Session();
                    $session->invalidate();
                    $em->remove($projectuser);
                    $em->flush();
                     $success="Your account no longer exists contact administrator for more info";
                    return $this->render('college/accessdenied.html.twig',['success'=>$success]);
            }
            else{
                    return $this->render('security/dashboard.html.twig',['finddept'=>$finddept,'displaystaff'=>$displaystaff]);
                 }
                        }
            else{
                      $success="Permission revoke..please contact your administrator for more info";
                      return $this->render('college/accessdenied.html.twig',['success'=>$success]);
            }
    }
      /**
     * @Route("/administator/user/remove/{id}", name="removeadmin")
     */
    public function removeadmin(Request $request,$id): Response
    {
            $user1=$this->getUser();
            $em=$this->getDoctrine()->getManager();
            $connection=$em->getConnection();
            if($user1!=null){
                    $userid=$this->getUser()->getId();
                    $projectuser =$this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $id]);
                    $statement3=$connection->prepare("select user.id as ID,department.name as department,user.name,user.email,user.phoneno,user.title,user.do_b,user.do_m,user.employeestatus,deptcom,user.gender from user inner join department on user.department_id=department.id");
                    $statement3->execute();
                    $displaystaff=$statement3->fetchAll();
            if($projectuser){
                    $em->remove($projectuser);
                    $em->flush();
                    return $this->redirectToRoute('adminuser');
            }
            else if($userid==$id){
                    $session = $this->get('session');
                    $session = new Session();
                    $session->invalidate();
                    $em->remove($projectuser);
                    $em->flush();
                     $success="Your account no longer exists contact administrator for more info";
                    return $this->render('college/accessdenied.html.twig',['success'=>$success]);
            }
            else{
                    return $this->render('security/admindepartment.html.twig',['displaystaff'=>$displaystaff]);
                 }
                        }
            else{
                      $success="Permission revoke..please contact your administrator for more info";
                      return $this->render('college/accessdenied.html.twig',['success'=>$success]);
            }
    }

         /**
     * @Route("/remove/staff/{id}/staffremoved/updateinfo", name="removestaff")
     */
    public function removestaff(Request $request,$id): Response
    {
               $user1=$this->getUser();
                $em=$this->getDoctrine()->getManager();
                $connection=$em->getConnection();
                if($user1!=null){
                        $userid=$this->getUser()->getId();
                        $projectavai =$this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $id]);
                        $finddept =$this->getDoctrine()->getRepository(Department::class)->findAll();
                        $statement3=$connection->prepare("select user.id as ID,department.name as department,user.name,user.email,user.phoneno,user.title,user.do_b,user.do_m,user.employeestatus,deptcom,user.gender from user inner join department on user.department_id=department.id");
                        $statement3->execute();
                        $displaystaff=$statement3->fetchAll();
                if($projectavai){
                        $em->remove($projectavai);
                        $em->flush();
                        return $this->redirectToRoute('dashboard');
                   }
                else if($userid==$id){
                        $session = $this->get('session');
                        $session = new Session();
                        $session->invalidate();
                        $em->remove($projectavai);
                        $em->flush();
                        $success="Your account no longer exists contact administrator for more info";
                        return $this->render('college/accessdenied.html.twig',['success'=>$success]);
                                      }
                else{
                         return $this->render('security/dashboard.html.twig',['finddept'=>$finddept,'displaystaff'=>$displaystaff]);
                     }
                }
                else{
                          $success="Permission revoke..please contact your administrator for more info";
                          return $this->render('college/accessdenied.html.twig',['success'=>$success]);
                   }
    }
        /**
        *remove undergraduate student
        *
     * @Route("/remove/undergraduate/student/available/just/{id}", name="removestudent")
     */
    public function removestudent(Request $request,$id): Response
    {
               $user1=$this->getUser();
                $em=$this->getDoctrine()->getManager();
                $connection=$em->getConnection();
                if($user1!=null){
                        $undergraduate =$this->getDoctrine()->getRepository(Student::class)->findOneBy(['id' => $id]);
                        $finddept =$this->getDoctrine()->getRepository(Department::class)->findAll();
                        $statement3=$connection->prepare("select student.id AS ID,department.name as department,student.regno,student.name,student.email,student.phoneno,student.gender,student.yo_s,student.level,student.citizenship from student inner join department on student.department_id=department.id ");
                        $statement3->execute();
                        $displaystaff=$statement3->fetchAll();
                if($undergraduate){
                        $em->remove($undergraduate);
                        $em->flush();
                     return $this->redirectToRoute('undergraduate');
                }
                }
                else{
                          $success="Permission revoke..please contact your administrator for more info";
                          return $this->render('college/accessdenied.html.twig',['success'=>$success]);
                   }
    }
       /**
        *remove postgraduate student
        *
     * @Route("/remove/postgraduate/student/available/{id}", name="removepost")
     */
    public function removepost(Request $request,$id): Response
    {
               $user1=$this->getUser();
                $em=$this->getDoctrine()->getManager();
                $connection=$em->getConnection();
                if($user1!=null){
                        $undergraduate =$this->getDoctrine()->getRepository(Student::class)->findOneBy(['id' => $id]);
                        $finddept =$this->getDoctrine()->getRepository(Department::class)->findAll();
                        $statement3=$connection->prepare("select student.id AS ID,department.name as department,student.regno,student.name,student.email,student.phoneno,student.gender,student.yo_s,student.level,student.citizenship from student inner join department on student.department_id=department.id ");
                        $statement3->execute();
                        $displaystaff=$statement3->fetchAll();
                if($undergraduate){
                        $em->remove($undergraduate);
                        $em->flush();
                          return $this->redirectToRoute('postgraduate');
                }
                }
                else{
                          $success="Permission revoke..please contact your administrator for more info";
                          return $this->render('college/accessdenied.html.twig',['success'=>$success]);
                   }
    }
       /**
        *remove staff promotion
        *
     * @Route("/remove/promotion/available/{id}/{test}", name="removepromotion")
     */
    public function removepromoted(Request $request,$id,$test): Response
    {
                $user1=$this->getUser();
               $promotion=new Promotion();
                $em=$this->getDoctrine()->getManager();
                if($user1!=null){
                        $promotion =$this->getDoctrine()->getRepository(Promotion::class)->findOneBy(['id' => $id]);
                        $promuser =$this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $test]);
                if($promotion){
                        $promotion->removePid($promuser);
                        $em->remove($promotion);
                        $em->flush();
                       return $this->redirectToRoute('promotion');
                             }
                }
                else{
                          $success="Permission revoke..please contact your administrator for more info";
                          return $this->render('college/accessdenied.html.twig',['success'=>$success]);
                   }
    }
    /**
        *remove project
        *
     * @Route("/remove/project/available/remain/empty/{id}", name="removeproject")
     */
    public function removeproject(Request $request,$id): Response
    {
                $user1=$this->getUser();
                $idnam=$user1->getId();
               $project=new Project();
                $em=$this->getDoctrine()->getManager();
                $connection=$em->getConnection();
                if($user1!=null){
                        $promoject =$this->getDoctrine()->getRepository(Project::class)->findOneBy(['id' => $id]);
                     if($promoject){
                            $em->remove($promoject);
                            $em->flush();
                           return $this->redirectToRoute('project');
                          }
                }
                else{
                          $success="Permission revoke..please contact your administrator for more info";
                          return $this->render('college/accessdenied.html.twig',['success'=>$success]);
                   }
    }
      /**
        *remove department
        *
     * @Route("/department/remove/available/remain/{id}", name="removedepartment")
     */
    public function removedepartment(Request $request,$id): Response
    {
                $user1=$this->getUser();
                $idnam=$user1->getId();
               $dept=new Department();
                $em=$this->getDoctrine()->getManager();
                $connection=$em->getConnection();
                if($user1!=null){
                        $deptpromo =$this->getDoctrine()->getRepository(Department::class)->findOneBy(['id' => $id]);
                     if($deptpromo){
                            $em->remove($deptpromo);
                            $em->flush();
                           return $this->redirectToRoute('department');
                          }
                }
                else{
                          $success="Permission revoke..please contact your administrator for more info";
                          return $this->render('college/accessdenied.html.twig',['success'=>$success]);
                   }
    }
    /**
    *@Route("/updatepromo/userinfo/available/{id}", name="editpromotion")
    *
    */
    public function promoedit(Request $request,$id):Response
    {
      $promotion =$this->getDoctrine()->getRepository(Promotion::class)->findOneBy(['id' => $id]);
        $form = $this->createFormBuilder($promotion)

        ->add('promofrom',TextType::class, array('label'=>'Promotion From','attr' => array('class' => 'form-control','readonly'=>'true')))
        ->add('promoto',TextType::class, array('label'=>'Promotion To','attr' => array('class' => 'form-control')))
        ->add('promodate',TextType::class, array('label'=>'Promotion Date','attr' => array('class' => 'form-control')))
        ->add('save',SubmitType::class, array(
            'label' => 'Save',
            'attr' => array('class' => 'btn btn-primary submit-btn')
        ))
        ->getForm();

        $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirect($this->generateUrl('promotion'));
        }
        return $this->render('security/editpromotion.html.twig',array('form'=>$form->createView()

                ));
    }
}
