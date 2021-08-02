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
use App\Entity\Programme;
use App\Entity\Course;
use App\Entity\Project;
class CollegeController extends AbstractController
{
 /**
     * @Route("/user/dashboard", name="dashboard")
     */
public function dashboard(Request $request,UserPasswordEncoderInterface $passwordEncoder): Response
{
$user1=$this->getUser();
   if($user1 != null)
       {
               $idnam=$user1->getId();
                $em=$this->getDoctrine()->getManager();
                $staff = new User();
                $fname=$request->request->get('fname');
                $lname=$request->request->get('lname');
                $email=$request->request->get('email');
                $phoneno=$request->request->get('phoneno');
                $gender=$request->request->get('gender');
                $birth=$request->request->get('birth');
                $join=$request->request->get('join');
                $dept=$request->request->get('dept');
                //$employee=$request->request->get('employee');
                $deptcom=$request->request->get('deptcom');
                $nation=$request->request->get('nation');
                $religion=$request->request->get('religion');
                $marital=$request->request->get('marital');
                $finddept =$this->getDoctrine()->getRepository(Department::class)->findAll();
                $takedept =$this->getDoctrine()->getRepository(Department::class)->findOneBy(['name' => $dept]);
                $connection=$em->getConnection();
                $statement2=$connection->prepare("select email from user where email =:email");
                $statement2->bindParam(':email', $email);
                $statement2->execute();
                $statement3=$connection->prepare("select user.userimage as APPIMAGE,user.id as thk,department.name as department,department.id as ID,user.name,user.email,user.phoneno,user.title,user.do_b,user.do_m,deptcom,user.gender from user inner join department on user.department_id=department.id where title='staff'");
                $statement3->execute();
                $statement5=$connection->prepare("select title from user where id=:idnam");
                $statement5->bindParam(':idnam', $idnam);
                $statement5->execute();
                $statement211=$connection->prepare("select user_id  as IDEA from leaves inner join leaves_user where status='approved'");
                $statement211->execute();
                $dis=$statement211->fetchAll();
                $statement6=$connection->prepare("select department_id as departmentID from user where id=:idnam");
                $statement6->bindParam(':idnam',$idnam);
                $statement6->execute();
                $display23=$statement6->fetchOne();
                $displaytest=$statement5->fetchOne();
                $displaystaff12=$statement3->fetchAll();
                $verifyemail=$statement2->fetchAll();
               if($request->isMethod('post'))
                {
                     if(!empty($fname) & !empty($lname) & !empty($nation) & !empty($religion) & !empty($marital)& !empty($dept)& !empty($email) & !empty($gender) & !empty($deptcom) & !empty($birth) & !empty($phoneno) & !empty($join))
                     {

                             if(!$verifyemail)
                              {
                                  if($takedept)
                                    {
                                        $staff->setName($fname.' '.$lname);
                                        $staff->setEmail($email);
                                        $staff->setGender($gender);
                                        $staff->setPhoneno($phoneno);
                                        $staff->setTitle('staff');
                                        $staff->setDoB($birth);
                                        $staff->setDoM($join);
                                        $staff->setPassword($passwordEncoder->encodePassword(
                                                                $staff,
                                                                $lname
                                                            ));
                                        $staff->setDeptcom($deptcom);
                                        $staff->setDepartment($takedept);
                                         $staff->setNationality($nation);
                                        $staff->setReligion($religion);
                                        $staff->setMaritalstatus($marital);
                                        $staff->setRoles([
                                                'ROLE_STAFF'
                                            ]);

                                        $em->persist($staff);
                                        $em->flush();
                                         return $this->redirectToRoute('dashboard');
                                    }
                                    else
                                    {
                                          $this->addFlash('warning',  'please add department first before you proceed..');
                                          return $this->render('security/dashboard.html.twig',['dis'=>$dis,'finddept'=>$finddept,'display23'=>$display23,'displaystaff'=>$displaystaff12,'displaytest'=>$displaytest]);
                                    }
                              }
                              else
                              {
                                     $this->addFlash('warning',  'User email already exists');
                                     return $this->render('security/dashboard.html.twig',['dis'=>$dis,'finddept'=>$finddept,'display23'=>$display23,'displaystaff'=>$displaystaff12,'displaytest'=>$displaytest]);
                              }
                     }
                     else
                     {
                                $this->addFlash('warning',  'All fields are required');
                                return $this->render('security/dashboard.html.twig',['dis'=>$dis,'finddept'=>$finddept,'display23'=>$display23,'displaystaff'=>$displaystaff12,'displaytest'=>$displaytest]);
                     }
               }

                    $this->addFlash('warning',  '');
                    return $this->render('security/dashboard.html.twig',['dis'=>$dis,'finddept'=>$finddept,'display23'=>$display23,'displaystaff'=>$displaystaff12,'displaytest'=>$displaytest]);
        }
        else
        {
            $success="You must sign in again to access you account";
            return $this->render('college/404.html.twig',['success'=>$success]);
        }
}
 /**
     * @Route("/admin/available", name="adminuser")
     */
public function adminuser(Request $request,UserPasswordEncoderInterface $passwordEncoder): Response
{
$user1=$this->getUser();
   if($user1 != null)
       {
               $idnam=$user1->getId();
                $em=$this->getDoctrine()->getManager();
                $staff = new User();
                $fname=$request->request->get('fname');
                $lname=$request->request->get('lname');
                $email=$request->request->get('email');
                $phoneno=$request->request->get('phoneno');
                $gender=$request->request->get('gender');
                $birth=$request->request->get('birth');
                $join=$request->request->get('join');
               // $employee=$request->request->get('employee');
                $deptcom=$request->request->get('deptcom');
                $nation=$request->request->get('nation');
                $religion=$request->request->get('religion');
                $marital=$request->request->get('marital');
                $finddept =$this->getDoctrine()->getRepository(Department::class)->findAll();
                $connection=$em->getConnection();
                $statement2=$connection->prepare("select email from user where email =:email");
                $statement2->bindParam(':email', $email);
                $statement2->execute();
                $statement3=$connection->prepare("select user.userimage as APPIMAGE ,user.id as ID,user.name,user.email,user.phoneno,user.title,user.do_b,user.do_m,deptcom,user.gender from user where title='administrator'");
                $statement3->execute();
                $statement5=$connection->prepare("select title from user where id=:idnam");
                $statement5->bindParam(':idnam', $idnam);
                $statement5->execute();
                $displaytest=$statement5->fetchOne();
                $displaystaff12=$statement3->fetchAll();
                $verifyemail=$statement2->fetchAll();
               if($request->isMethod('post'))
                {
                     if(!empty($fname) & !empty($lname) & !empty($nation) & !empty($religion) & !empty($marital)& !empty($email) & !empty($gender)  & !empty($deptcom) & !empty($birth) & !empty($phoneno) & !empty($join))
                     {

                             if(!$verifyemail)
                              {

                                        $staff->setName($fname.' '.$lname);
                                        $staff->setEmail($email);
                                        $staff->setGender($gender);
                                        $staff->setPhoneno($phoneno);
                                        $staff->setTitle('administrator');
                                        $staff->setDoB($birth);
                                        $staff->setDoM($join);
                                        $staff->setPassword($passwordEncoder->encodePassword(
                                                                $staff,
                                                                $lname
                                                            ));
                                        $staff->setDeptcom($deptcom);
                                        $staff->setDepartment(null);
                                        $staff->setNationality($nation);
                                        $staff->setReligion($religion);
                                        $staff->setMaritalstatus($marital);
                                        $staff->setRoles([
                                                'ROLE_ADMIN'
                                            ]);

                                        $em->persist($staff);
                                        $em->flush();
                                         return $this->redirectToRoute('adminuser');

                              }
                              else
                              {
                                     $this->addFlash('admintest',  'User email already exists');
                                     return $this->render('security/admindepartment.html.twig',['displaystaff'=>$displaystaff12,'displaytest'=>$displaytest,'finddept'=>$finddept]);
                              }
                     }
                     else
                     {
                                $this->addFlash('admintest',  'All fields are required');
                                return $this->render('security/admindepartment.html.twig',['displaystaff'=>$displaystaff12,'displaytest'=>$displaytest,'finddept'=>$finddept]);
                     }
               }

                    $this->addFlash('admintest',  '');
                    return $this->render('security/admindepartment.html.twig',['displaystaff'=>$displaystaff12,'displaytest'=>$displaytest,'finddept'=>$finddept]);
        }
        else
        {
            $success="You must sign in again to access you account";
            return $this->render('college/404.html.twig',['success'=>$success]);
        }
}
 /**
     * @Route("/user/dashboard/department", name="department")
     */
public function department(Request $request): Response
{

    $user1=$this->getUser();
        if($user1 != null )
        {
           $finddept =$this->getDoctrine()->getRepository(Department::class)->findAll();
            $em=$this->getDoctrine()->getManager();
            $department =new Department();
            $dept=$request->request->get('deptname');
            if($request->isMethod('post'))
            {
              if(!empty($dept))
               {
                    $department->setName($dept);
                    $em->persist($department);
                    $em->flush();
                    return $this->redirectToRoute('department');
                }
               else
                {
                 $this->addFlash('danger',  'This field is required!..');
                 return $this->render('security/department.html.twig',['finddept'=>$finddept]);
                }
                  $this->addFlash('danger',  '');
                  return $this->render('security/department.html.twig',['finddept'=>$finddept]);
             }
                  $this->addFlash('danger',  '');
                 return $this->render('security/department.html.twig',['finddept'=>$finddept]);
        }
        else
        {
            $success="You must sign in again to access you account";
            return $this->render('college/404.html.twig',['success'=>$success]);
        }
}
       /**
     * @Route("/{id}/edit/name",name="edit")
     */
public function edit(Request $request,$id)
{
        $department=new Department();
        $department4 =$this->getDoctrine()->getRepository(Department::class)->findOneBy(['id' => $id]);
        $department_name=$department4->getName();
        $form = $this->createFormBuilder($department4)

        ->add('name',TextType::class, array('label'=>'Department Name','attr' => array('class' => 'form-control')))
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
            return $this->redirect($this->generateUrl('department'));
        }
        return $this->render('security/edit.html.twig',array('form'=>$form->createView()

                ));
}
     /**
     * @Route("/user/dashboard/project/available", name="project")
     */
public function project(Request $request): Response
 {
    $user1=$this->getUser();
    if($user1 != null )
       {
           $idnam=$user1->getId();
           $title=$user1->getTitle();
           $finddept =$this->getDoctrine()->getRepository(Department::class)->findAll();
            $em=$this->getDoctrine()->getManager();
            $connection=$em->getConnection();
            $statement7=$connection->prepare("select project.id,department.name as department,department.id AS ID,project.name as project,project.priority as priority,project.status as status,project.startdate as start,project.deadline as end,project.content as content from project,department inner join project_department on department_id=department.id where project_id=project.id");
            $statement7->execute();
            $statement3=$connection->prepare("select department_id as departmentID from user where id=:idnam");
            $statement3->bindParam(':idnam',$idnam);
            $statement3->execute();
            $display=$statement7->fetchAll();
            $display23=$statement3->fetchOne();
            $project =new Project();
            $projectname=$request->request->get('projectname');
            $start=$request->request->get('start');
            $end=$request->request->get('end');
            $priority=$request->request->get('priority');
            $descr=$request->request->get('descr');
            $deptname=$request->request->get('dept');
            if($request->isMethod('post'))
                {

                                 /**...hod add project*/
                  if($title=='HoDs'){
                      if(!empty($projectname) & !empty($end) & !empty($priority) & !empty($descr) & !empty($start))
                        {
                         $takehod =$this->getDoctrine()->getRepository(Department::class)->findOneBy(['name'=>$display23]);


                            if($takehod)
                                {
                                    $project->setName($projectname);
                                    $project->addDepartment($takehod);
                                    $project->setStartdate($start);
                                    $project->setDeadline($end);
                                    $project->setContent($descr);
                                    $project->setPriority($priority);
                                    $project->setStatus('active');
                                    $em->persist($project);
                                    $em->flush();
                                    return $this->redirectToRoute('project');
                                }
                                    $this->addFlash('project',  'Please add department first!..');
                                    return $this->render('security/project.html.twig',['finddept'=>$finddept,'display'=>$display,'display23'=>$display23]);
                        }
                     else
                        {
                          $this->addFlash('project',  'All fields are required!..');
                           return $this->render('security/project.html.twig',['finddept'=>$finddept,'display'=>$display,'display23'=>$display23]);
                        }
                    }

                /** head hod project set*/



                 if(!empty($projectname) & !empty($deptname) & !empty($end) & !empty($priority) & !empty($descr) & !empty($start))
                    {
                    $takedept =$this->getDoctrine()->getRepository(Department::class)->findOneBy(['name'=>$deptname]);


                        if($takedept)
                            {
                                $project->setName($projectname);
                                $project->addDepartment($takedept);
                                $project->setStartdate($start);
                                $project->setDeadline($end);
                                $project->setContent($descr);
                                $project->setPriority($priority);
                                $project->setStatus('active');
                                $em->persist($project);
                                $em->flush();
                                return $this->redirectToRoute('project');
                            }
                                $this->addFlash('project',  'Please add department first!..');
                                return $this->render('security/project.html.twig',['finddept'=>$finddept,'display'=>$display,'display23'=>$display23]);
                    }
                 else
                    {
                      $this->addFlash('project',  'All fields are required!..');
                       return $this->render('security/project.html.twig',['finddept'=>$finddept,'display'=>$display,'display23'=>$display23]);
                    }
                }

                  $this->addFlash('project',  '');
                  return $this->render('security/project.html.twig',['finddept'=>$finddept,'display'=>$display,'display23'=>$display23]);
       }
      else
        {
            $success="You must sign in again to access you account";
            return $this->render('college/404.html.twig',['success'=>$success]);
        }
 }


        /**
     * @Route("/project/{id}/edit/name",name="projectedit")
     */
public function projectedit(Request $request,$id)
{
        $project=new Project();
        $project4 =$this->getDoctrine()->getRepository(Project::class)->findOneBy(['id' => $id]);
        $project_name=$project->getName();
        $form = $this->createFormBuilder($project4)

        ->add('name',TextType::class, array('label'=>'Project Name','attr' => array('class' => 'form-control')))
        ->add('startdate',TextType::class, array('label'=>'Start Date','attr' => array('class' => 'form-control')))
        ->add('deadline',TextType::class, array('label'=>'End Date','attr' => array('class' => 'form-control')))
        ->add('priority',TextType::class, array('label'=>'Priority','attr' => array('class' => 'form-control')))
        ->add('status',TextType::class, array('label'=>'Status','attr' => array('class' => 'form-control')))
        ->add('content',TextareaType::class, array('label'=>'Description','attr' => array('class' => 'form-control','rows'=>'3')))
        ->add('save',SubmitType::class, array(
            'label' => 'save',
            'attr' => array('class' => 'btn btn-primary submit-btn')
        ))
        ->getForm();

        $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirect($this->generateUrl('project'));
        }
        return $this->render('security/projection.html.twig',array('form'=>$form->createView()

                ));
}
      /**
     * @Route("/dashboard/{proj}/{id}/adduser", name="adduser")
     */
public function adduser(Request $request,$id,$proj): Response
{
   $user1=$this->getUser();
   if($user1 != null )
        {
            $em=$this->getDoctrine()->getManager();
            $connection=$em->getConnection();
            $statement7=$connection->prepare("select project_id as lafesa,user.userimage as APP,user.id as ID,user.name as username,user.email as email from user inner join project_user on user_id=user.id where project_id=:id");
            $statement7->bindParam(':id',$id);
            $statement7->execute();
            $statement9=$connection->prepare("select department_id from department inner join project_department on department_id=department.id where project_id=:id");
            $statement9->bindParam(':id',$id);
            $statement9->execute();
            $project=$request->request->get('username');
            $statement10=$connection->prepare("select department_id from user where email=:project");
            $statement10->bindParam(':project',$project);
            $statement10->execute();
            $statement11=$connection->prepare("select id from user where email=:project");
            $statement11->bindParam(':project',$project);
            $statement11->execute();
            $disp=$statement11->fetchOne();
            $statement8=$connection->prepare("select user_id from project_user where user_id=:disp and project_id=:id");
            $statement8->bindParam(':id',$id);
            $statement8->bindParam(':disp',$disp);
            $statement8->execute();
            $displaydept=$statement9->fetchAll();
            $displayuserdept=$statement10->fetchAll();
            $display=$statement7->fetchAll();
            $displayverify=$statement8->fetchAll();
            $user=new User();
            $projection= new Project();
             if($request->isMethod('post'))
             {
                  if(!empty($project))
                    {
                      $projectname =$this->getDoctrine()->getRepository(User::class)->findOneBy(['email'=>$project]);
                       if($projectname)
                           {
                                if($displaydept==$displayuserdept)
                                {
                                    if(!$displayverify)
                                        {
                                             $projection =$this->getDoctrine()->getRepository(Project::class)->findOneBy(['id' => $id]);
                                             $projection->setName($proj);
                                             $projection->addAdduser($projectname);
                                             $em->flush();
                                             return $this->redirect($this->generateUrl('adduser',['id'=>$id,'proj'=>$proj]));
                                        }
                                        else
                                        {
                                          $this->addFlash('addme', 'User email already assign to this project!..');
                                           return $this->render('security/assignuser.html.twig',['display'=>$display,'projectname'=>$proj]);
                                          }
                                }
                                else
                                {
                                          $this->addFlash('addme', 'User mail not found on specified project department!..');
                                           return $this->render('security/assignuser.html.twig',['display'=>$display,'projectname'=>$proj]);
                                }
                           }
                           else
                           {
                                  $this->addFlash('addme','User email not found!..');
                                   return $this->render('security/assignuser.html.twig',['display'=>$display,'projectname'=>$proj]);
                           }
                    }
                    else
                     {
                          $this->addFlash('addme',  'This field is required!..');
                           return $this->render('security/assignuser.html.twig',['display'=>$display,'projectname'=>$proj]);
                     }
             }
             $this->addFlash('addme',  'Please make sure staff..that you need to assign is within project department before you proceed..');
             return $this->render('security/assignuser.html.twig',['display'=>$display,'projectname'=>$proj]);
        }
        else
        {
                $success="You must sign in again to access you account";
                return $this->render('college/404.html.twig',['success'=>$success]);
        }
 }
      /**
     * @Route("/user/dashboard/profile", name="profile")
     */
public function profile(): Response
{
    $user1=$this->getUser();
        if($user1 != null)
        {
            $user2=$this->getUser()->getEmail();
            $userid=$user1->getId();
            $em=$this->getDoctrine()->getManager();
            $display1=$this->getDoctrine()->getRepository(User::class)->findBy(['email' =>$user2]);
            $connection=$em->getConnection();
            $statement2=$connection->prepare("select user_id  as IDEA from leaves inner join leaves_user where status='approved' and user_id='".$userid."'");
            $statement2->execute();
            $dis=$statement2->fetchOne();
            return $this->render('security/profile.html.twig',['display1'=>$display1,'user1'=>$user1,'dis'=>$dis]);
        }
        else
        {
            $success="You must sign in again to access you account";
            return $this->render('college/404.html.twig',['success'=>$success]);
        }
}
 /**
     * @Route("/admin/dashboard/page", name="pass")
     */
public function pass(Request $request): Response
{
     $user1=$this->getUser();
     if($user1 != null)
        {
                $username=$this->getUser()->getName();

               //$ip=$request->request->getClientIp();
               $em=$this->getDoctrine()->getManager();
                $connection=$em->getConnection();
                $statement2=$connection->prepare("select count(*) from department");
                $statement2->execute();
                $statement3=$connection->prepare("select count(*) from user where title='HoDs'");
                $statement3->execute();
                $statement4=$connection->prepare("select count(*) from user where title='staff'");
                $statement4->execute();
                $statement45=$connection->prepare("select count(*) from user where title='administrator'");
                $statement45->execute();
                $statement5=$connection->prepare("select count(*) from student where level='undergraduate'");
                $statement5->execute();
                $statement51=$connection->prepare("select count(*) from student where level='postgraduate'");
                $statement51->execute();
                $statement52=$connection->prepare("select count(*) from project_department");
                $statement52->execute();
                $statement6=$connection->prepare("select department.name as department,user.name,user.title,user.deptcom from user inner join department on user.department_id= department.id where title='HoDs' limit 4");
                $statement6->execute();
                $statement7=$connection->prepare("select project.id,department.name as department,project.name as project from project,department inner join project_department on department_id=department.id where project_id=project.id limit 4");
                $statement7->execute();
                $today=new \DateTime("now");
                $today=$today->format('Y-m-d');
                $statement12=$connection->prepare("select count(*) from leaves where status='approved' and approveddate='".$today."'");
                $statement12->execute();
                $statement13=$connection->prepare("select count(*) from leaves where status='pending' and approveddate='".$today."'");
                $statement13->execute();
                $statement34=$connection->prepare("select leaves.approvedimage as APPIMAGE,user.userimage,user.name as Emp,leaves.lfrom as FRM,leaves.status as ST from user inner join leaves_user inner join leaves on leaves.id=leaves_id where user.id=user_id and (status='approved' or status='pending') and approveddate='".$today."' limit 2");
                $statement34->execute();
                $statement314=$connection->prepare("select userimage as APP,name as Emp,last_activity_at as LAT from user where last_activity_at is not null order by last_activity_at desc limit 2");
                $statement314->execute();
                $statement318=$connection->prepare("select count(*) from user where last_activity_at is not null");
                $statement318->execute();
                $display=$statement7->fetchAll();
                $displayt=$statement318->fetchOne();
                $last_activity_at=$statement314->fetchAll();
                $display124=$statement34->fetchAll();
                $displaystaff=$statement6->fetchAll();
                $findstudent=$statement5->fetchOne();
                $findpost=$statement51->fetchOne();
                $findproject=$statement52->fetchOne();
                $findHods=$statement3->fetchOne();
                $findstaff=$statement4->fetchOne();
                $findadmin=$statement45->fetchOne();
                $finddept=$statement2->fetchOne();
                $absent=$statement12->fetchOne();
                $pending=$statement13->fetchOne();
                return $this->render('college/admindashboard.html.twig',['last_activity_at'=>$last_activity_at,'absent'=>$absent,'pending'=>$pending,'findproject'=>$findproject,'finddept'=>$finddept,'findadmin'=>$findadmin,'findpost'=>$findpost,'username'=>$username,'findHods'=>$findHods,'findstudent'=>$findstudent,'display124'=>$display124,'display'=>$display,'displayt'=>$displayt,'displaystaff'=>$displaystaff,'findstaff'=>$findstaff]);
        }
        else
        {
                $success="You must sign in again to access you account";
                return $this->render('college/404.html.twig',['success'=>$success]);
        }
}
 /**
     * @Route("/user/dashboard/undergraduate", name="undergraduate")
     */
public function student(Request $request): Response
 {

    $user1=$this->getUser();
    if($user1 != null )
        {
            $idnam=$user1->getId();
            $em=$this->getDoctrine()->getManager();
            $student = new Student();
            $fname=$request->request->get('fname');
            $lname=$request->request->get('lname');
            $email=$request->request->get('email');
            $phoneno=$request->request->get('phoneno');
            $gender=$request->request->get('gender');
            $ctz=$request->request->get('citizen');
            $yos=$request->request->get('yos');
            $regno=$request->request->get('regno');
            $dept=$request->request->get('dept');
            $pname=$request->request->get('pname');
            $finddept =$this->getDoctrine()->getRepository(Department::class)->findAll();
            $findProg =$this->getDoctrine()->getRepository(Programme::class)->findAll();
            $takedept =$this->getDoctrine()->getRepository(Department::class)->findOneBy(['name' => $dept]);
            $prog =$this->getDoctrine()->getRepository(Programme::class)->findOneBy(['name' => $pname]);
            $connection=$em->getConnection();
            $statement2=$connection->prepare("select regno from student where regno =:regno");
            $statement2->bindParam(':regno', $regno);
            $statement2->execute();
            $statement3=$connection->prepare(" select student.id AS id,department.name as department,department.id as ID,student.regno,student.name,student.email,student.phoneno,student.gender,student.yo_s,student.level,student.citizenship,programme.name as PNAME from student inner join department on student.department_id=department.id inner join programme on programme.id=studentpro_id");
            $statement3->execute();
            $statement5=$connection->prepare("select title from user where id=:idnam");
            $statement5->bindParam(':idnam', $idnam);
            $statement5->execute();
            $statement6=$connection->prepare("select department_id from user where id='".$idnam."'");
            $statement6->execute();
            $display23=$statement6->fetchOne();
            $statement43=$connection->prepare("select count(*) from student where level='undergraduate'");
            $statement43->execute();
            $displaytest=$statement5->fetchOne();
            $none=$statement43->fetchOne();
            $displaystaff=$statement3->fetchAll();
            $verifyregno=$statement2->fetchAll();
            if($request->isMethod('post'))
                   {
                      if(!empty($fname) & !empty($lname) &!empty($pname) & !empty($dept)& !empty($email) & !empty($gender) & !empty($yos) & !empty($regno) & !empty($ctz) & !empty($phoneno))
                          {
                            if(!$verifyregno)
                                {
                                  if($takedept)
                                    {
                                    if($prog){
                                        $student->setName($fname.' '.$lname);
                                        $student->setEmail($email);
                                        $student->setGender($gender);
                                        $student->setRegno($regno);
                                        $student->setPhoneno($phoneno);
                                        $student->setYoS($yos);
                                        $student->setCitizenship($ctz);
                                        $student->setLevel('undergraduate');
                                        $student->setDepartment($takedept);
                                        $student->setStudentpro($prog);
                                        $em->persist($student);
                                        $em->flush();
                                        return $this->redirectToRoute('undergraduate');
                                        }
                                        else{
                                         $this->addFlash('student',  'Please add programme first before you proceed..');
                                        return $this->render('security/studentdetail.html.twig',['findProg'=>$findProg,'none'=>$none,'finddept'=>$finddept,'displaystaff'=>$displaystaff,'displaytest'=>$displaytest,'display23'=>$display23]);
                                        }
                                    }
                                    else
                                    {
                                        $this->addFlash('student',  'Please add department first before you proceed..');
                                        return $this->render('security/studentdetail.html.twig',['findProg'=>$findProg,'none'=>$none,'finddept'=>$finddept,'displaystaff'=>$displaystaff,'displaytest'=>$displaytest,'display23'=>$display23]);
                                    }
                               }
                               else
                               {
                                    $this->addFlash('student',  'Registration number already exist!');
                                    return $this->render('security/studentdetail.html.twig',['findProg'=>$findProg,'none'=>$none,'finddept'=>$finddept,'displaystaff'=>$displaystaff,'displaytest'=>$displaytest,'display23'=>$display23]);
                                }
                          }
                          else
                          {
                                 $this->addFlash('student',  'All fields are required!');
                                 return $this->render('security/studentdetail.html.twig',['findProg'=>$findProg,'none'=>$none,'finddept'=>$finddept,'displaystaff'=>$displaystaff,'displaytest'=>$displaytest,'display23'=>$display23]);
                          }
                   }
                   $this->addFlash('student',  'Noted! remember to add valid student information');
                   return $this->render('security/studentdetail.html.twig',['findProg'=>$findProg,'none'=>$none,'finddept'=>$finddept,'displaystaff'=>$displaystaff,'display23'=>$display23,'displaytest'=>$displaytest]);
        }
        else
        {
            $success="You must sign in again to access you account";
            return $this->render('college/404.html.twig',['success'=>$success]);
        }
}

      /**
     * @Route("/student/area/{id}/edit/name",name="editpath")
     */
public function editpath(Request $request,$id)
{
        $student=new Student();
        $user4 =$this->getDoctrine()->getRepository(Student::class)->findOneBy(['id' => $id]);
        $form = $this->createFormBuilder($user4)

        ->add('name',TextType::class, array('label'=>'Full Name','attr' => array('class' => 'form-control')))
        ->add('email',EmailType::class, array('label'=>'Email','attr' => array('class' => 'form-control')))
        ->add('gender',TextType::class, array('label'=>'Gender','attr' => array('class' => 'form-control')))
        ->add('regno',TextType::class, array('label'=>'Registration No','attr' => array('class' => 'form-control')))
        ->add('citizenship',TextType::class, array('label'=>'Citizenship','attr' => array('class' => 'form-control')))
        ->add('yos',TextType::class, array('label'=>'Year of study','attr' => array('class' => 'form-control')))
        ->add('phoneno',TextType::class, array('label'=>'Phone No','attr' => array('class' => 'form-control')))
        ->add('save',SubmitType::class, array(
            'label' => 'save',
            'attr' => array('class' => 'btn btn-primary submit-btn')
        ))
        ->getForm();

        $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirect($this->generateUrl('undergraduate'));
        }
        return $this->render('security/editstudent.html.twig',array('form'=>$form->createView()

                ));
}
    /**
     * @Route("/user/dashboard/postgraduate/student", name="postgraduate")
     */
public function poststudent(Request $request): Response
{
     $user1=$this->getUser();
     $idnam=$user1->getId();
     if($user1 != null )
        {
            $em=$this->getDoctrine()->getManager();
            $student = new Student();
            $fname=$request->request->get('fname');
            $lname=$request->request->get('lname');
            $email=$request->request->get('email');
            $phoneno=$request->request->get('phoneno');
            $gender=$request->request->get('gender');
            $ctz=$request->request->get('citizen');
            $yos=$request->request->get('yos');
            $regno=$request->request->get('regno');
            $dept=$request->request->get('dept');
            $pname=$request->request->get('pname');
            $finddept =$this->getDoctrine()->getRepository(Department::class)->findAll();
            $findProg =$this->getDoctrine()->getRepository(Programme::class)->findAll();
            $takedept =$this->getDoctrine()->getRepository(Department::class)->findOneBy(['name' => $dept]);
            $prog =$this->getDoctrine()->getRepository(Programme::class)->findOneBy(['name' => $pname]);
            $connection=$em->getConnection();
            $statement2=$connection->prepare("select regno from student where regno =:regno");
            $statement2->bindParam(':regno', $regno);
            $statement2->execute();
            $statement5=$connection->prepare("select title from user where id=:idnam");
            $statement5->bindParam(':idnam', $idnam);
            $statement5->execute();
            $displaytest=$statement5->fetchOne();
            $statement3=$connection->prepare(" select student.id AS id,department.name as department,department.id as ID,student.regno,student.name,student.email,student.phoneno,student.gender,student.yo_s,student.level,student.citizenship,programme.name as PNAME from student inner join department on student.department_id=department.id inner join programme on programme.id=studentpro_id");
            $statement3->execute();
            $statement6=$connection->prepare("select department_id as departmentID from user where id=:idnam");
            $statement6->bindParam(':idnam',$idnam);
            $statement6->execute();
            $display23=$statement6->fetchOne();
            $statement43=$connection->prepare("select count(*) from student where  level='postgraduate'");
            $statement43->execute();
            $prove=$statement43->fetchOne();
            $displaystaff=$statement3->fetchAll();
            $verifyregno=$statement2->fetchAll();
            if($request->isMethod('post'))
                {
                   if(!empty($fname) & !empty($pname) & !empty($lname) & !empty($dept)& !empty($email) & !empty($gender) & !empty($yos) & !empty($regno) & !empty($ctz) & !empty($phoneno))
                        {
                             if(!$verifyregno)
                                {
                                    if($takedept)
                                      {
                                        if($prog){
                                        $student->setName($fname.' '.$lname);
                                        $student->setEmail($email);
                                        $student->setGender($gender);
                                        $student->setRegno($regno);
                                        $student->setPhoneno($phoneno);
                                        $student->setYoS($yos);
                                        $student->setCitizenship($ctz);
                                        $student->setLevel('undergraduate');
                                        $student->setDepartment($takedept);
                                        $student->setStudentpro($prog);
                                        $em->persist($student);
                                        $em->flush();
                                        return $this->redirectToRoute('postgraduate');
                                        }
                                        else{
                                         $this->addFlash('post',  'Please add programme first before you proceed..');
                                        return $this->render('security/poststudent.html.twig',['findProg'=>$findProg,'prove'=>$prove,'finddept'=>$finddept,'displaystaff'=>$displaystaff,'display23'=>$display23,'displaytest'=>$displaytest]);
                                        }
                                    }
                                      else
                                      {
                                          $this->addFlash('post',  'Please add department first before you proceed..');
                                          return $this->render('security/poststudent.html.twig',['findProg'=>$findProg,'prove'=>$prove,'finddept'=>$finddept,'displaystaff'=>$displaystaff,'display23'=>$display23,'displaytest'=>$displaytest]);
                                       }
                                }
                             else
                                {
                                        $this->addFlash('post',  'Registration number already exist!');
                                           return $this->render('security/poststudent.html.twig',['findProg'=>$findProg,'prove'=>$prove,'finddept'=>$finddept,'displaystaff'=>$displaystaff,'display23'=>$display23,'displaytest'=>$displaytest]);
                                }
                        }
                        else
                        {
                             $this->addFlash('post',  'All fields are required!');
                            return $this->render('security/poststudent.html.twig',['findProg'=>$findProg,'prove'=>$prove,'finddept'=>$finddept,'displaystaff'=>$displaystaff,'display23'=>$display23,'displaytest'=>$displaytest]);
                        }
                }
                        $this->addFlash('post',  'Noted! remember to add valid student information');
                           return $this->render('security/poststudent.html.twig',['findProg'=>$findProg,'prove'=>$prove,'finddept'=>$finddept,'displaystaff'=>$displaystaff,'display23'=>$display23,'displaytest'=>$displaytest]);
        }
        else
        {
                $success="You must sign in again to access you account";
                return $this->render('college/404.html.twig',['success'=>$success]);
        }
}
        /**
     * @Route("/student/post/area/{id}/edit/name",name="editpost")
     */
public function editpost(Request $request,$id)
{
        $student=new Student();
        $user4 =$this->getDoctrine()->getRepository(Student::class)->findOneBy(['id' => $id]);
        $form = $this->createFormBuilder($user4)

        ->add('name',TextType::class, array('label'=>'Full Name','attr' => array('class' => 'form-control')))
        ->add('email',EmailType::class, array('label'=>'Email','attr' => array('class' => 'form-control')))
        ->add('gender',TextType::class, array('label'=>'Gender','attr' => array('class' => 'form-control')))
        ->add('regno',TextType::class, array('label'=>'Registration No','attr' => array('class' => 'form-control')))
        ->add('citizenship',TextType::class, array('label'=>'Citizenship','attr' => array('class' => 'form-control')))
        ->add('yos',TextType::class, array('label'=>'Year of study','attr' => array('class' => 'form-control')))
        ->add('phoneno',TextType::class, array('label'=>'Phone No','attr' => array('class' => 'form-control')))
        ->add('save',SubmitType::class, array(
            'label' => 'save',
            'attr' => array('class' => 'btn btn-primary submit-btn')
        ))
        ->getForm();

        $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        return $this->redirect($this->generateUrl('postgraduate'));
        }
        return $this->render('security/editpost.html.twig',array('form'=>$form->createView()

                ));
    }
    /**
     * @Route("/user/dashboard/security", name="security")
     */
public function security(): Response
{
       $user1=$this->getUser();
        if($user1 != null){
           $this->addFlash('success',"");
            return $this->render('college/changepassword.html.twig');
        }
        else
        {
            $success="You must sign in again to access you account";
            return $this->render('college/404.html.twig',['success'=>$success]);
        }
}
     /**
        *
        * @Route("/dashboard/setting/security/updpass",name="app_update_pass")
        *
        *
        *
        */
public function updpass(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
{
$user1=$this->getUser();
    if($user1 != null)
        {
            $oldpass=strip_tags($request->request->get('oldpass'));
            $user=$this->getUser();
            $encold= $passwordEncoder->isPasswordValid($user,$oldpass);
            $newpass=strip_tags($request->request->get('newpass'));
            $confirm=strip_tags($request->request->get('conf'));
          if($request->isMethod('POST') && !empty($oldpass) && !empty($newpass) && !empty($confirm))
            {
               if($encold==true)
                  {
                      if(strlen($newpass)>7)
                        {
                             if($newpass==$confirm)
                                 {
                                    $conf=$passwordEncoder->encodePassword($user,$confirm);
                                    $user->setPassword($conf);
                                    $entityManager = $this->getDoctrine()->getManager();
                                    $entityManager->persist($user);
                                    $entityManager->flush();
                                     $session = $this->get('session');
                                     $session = new Session();
                                     $session->invalidate();
                                     return $this->redirect($this->generateUrl('app_login'));
                                 }
                             else
                                 {
                                      $this->addFlash('success',  'Password mismatch!');
                                      return $this->render('college/changePassword.html.twig');
                                  }
                        }
                      else
                        {
                             $this->addFlash('success','Password must be at least 8 characters');
                             return $this->render('college/changePassword.html.twig');
                         }
                  }
                  else
                  {
                        $this->addFlash('success','The current password is incorrect');
                        return $this->render('college/changePassword.html.twig');
                  }

            }
            else
            {
                  $this->addFlash('success','All fields are required');
                  return $this->render('college/changePassword.html.twig');
            }
        }
        else
        {
            $success="You must sign in to access you account";
            return $this->render('college/404.html.twig',['success'=>$success]);
       }
}
    /**
     * @Route("/user/dashboard/HoDs", name="head")
     */
public function hod(Request $request,UserPasswordEncoderInterface $passwordEncoder): Response
 {

 $user1=$this->getUser();
 if($user1 != null)
    {
        $idnam=$user1->getId();
        $em=$this->getDoctrine()->getManager();
        $staff = new User();
        $fname=$request->request->get('fname');
        $lname=$request->request->get('lname');
        $email=$request->request->get('email');
        $phoneno=$request->request->get('phoneno');
        $gender=$request->request->get('gender');
        $birth=$request->request->get('birth');
        $join=$request->request->get('join');
        $dept=$request->request->get('dept');
       // $employee=$request->request->get('employee');
        $nation=$request->request->get('nation');
        $religion=$request->request->get('religion');
        $marital=$request->request->get('marital');
        $deptcom=$request->request->get('deptcom');
        $finddept =$this->getDoctrine()->getRepository(Department::class)->findAll();
        $takedept =$this->getDoctrine()->getRepository(Department::class)->findOneBy(['name' => $dept]);
        $connection=$em->getConnection();
        $statement2=$connection->prepare("select email from user where email =:email");
        $statement2->bindParam(':email', $email);
        $statement2->execute();
        $statement3=$connection->prepare("select user.userimage as APPIMAGE,user.id as ID,department.name as department,user.name,user.email,user.phoneno,user.title,user.do_b,user.do_m,deptcom,user.gender from user inner join department on user.department_id=department.id where title='HoDs' ");
        $statement3->execute();
        $displaystaff=$statement3->fetchAll();
        $statement5=$connection->prepare("select title from user where id=:idnam");
        $statement5->bindParam(':idnam', $idnam);
        $statement5->execute();
        $displaytest=$statement5->fetchOne();
        $verifyemail=$statement2->fetchAll();
        if($request->isMethod('post'))
            {
               if(!empty($fname) & !empty($lname) & !empty($dept)& !empty($nation) & !empty($religion) & !empty($marital)&!empty($email) & !empty($gender)  & !empty($deptcom) & !empty($birth) & !empty($phoneno)& !empty($join))
                    {
                        if(!$verifyemail)
                            {
                                if($takedept)
                                   {
                                        $staff->setName($fname.' '.$lname);
                                        $staff->setEmail($email);
                                        $staff->setGender($gender);
                                        $staff->setPhoneno($phoneno);
                                        $staff->setTitle('HoDs');
                                        $staff->setDoB($birth);
                                        $staff->setDoM($join);
                                        $staff->setPassword($passwordEncoder->encodePassword(
                                                                $staff,
                                                                $lname
                                                            ));
                                        $staff->setDeptcom($deptcom);
                                        $staff->setDepartment($takedept);
                                        $staff->setNationality($nation);
                                        $staff->setReligion($religion);
                                        $staff->setMaritalstatus($marital);
                                        $staff->setRoles([
                                                    'ROLE_HODS'
                                                      ]);
                                        $em->persist($staff);
                                        $em->flush();
                                        return $this->redirectToRoute('head');
                                   }
                                  else
                                    {
                                          $this->addFlash('success',  'please add department first before you proceed..');
                                          return $this->render('security/headofdept.html.twig',['finddept'=>$finddept,'displaystaff'=>$displaystaff,'displaytest'=>$displaytest]);
                                    }
                            }
                         else
                            {
                                    $this->addFlash('success',  'User email already exists');
                                    return $this->render('security/headofdept.html.twig',['finddept'=>$finddept,'displaystaff'=>$displaystaff,'displaytest'=>$displaytest]);
                            }
                    }
                    else
                    {
                            $this->addFlash('success',  'All fields are required');
                            return $this->render('security/headofdept.html.twig',['finddept'=>$finddept,'displaystaff'=>$displaystaff,'displaytest'=>$displaytest]);
                    }
            }

            $this->addFlash('success',  '');
            return $this->render('security/headofdept.html.twig',['finddept'=>$finddept,'displaystaff'=>$displaystaff,'displaytest'=>$displaytest]);
    }
    else
    {
            $success="You must sign in again to access you account";
            return $this->render('college/404.html.twig',['success'=>$success]);
    }
}
    /**
     * @Route("/HoDs/{id}/edit/name",name="editInfo")
     */
public function editinfo(Request $request,$id)
{
  $User=new User();
        $user4 =$this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $id]);

        $form = $this->createFormBuilder($user4)
        ->add('name',TextType::class, array('label'=>'Full Name','attr' => array('class' => 'form-control')))
        ->add('email',EmailType::class, array('label'=>'Email','attr' => array('class' => 'form-control')))
        ->add('gender',TextType::class, array('label'=>'Gender','attr' => array('class' => 'form-control')))
        ->add('DoB',TextType::class, array('label'=>'Date of birth','attr' => array('class' => 'form-control')))
        ->add('DoM',TextType::class, array('label'=>'Joining Date','attr' => array('class' => 'form-control')))
        ->add('title',TextType::class, array('label'=>'Title','attr' => array('class' => 'form-control')))
        ->add('phoneno',TextType::class, array('label'=>'Phone No','attr' => array('class' => 'form-control')))
        ->add('maritalstatus',TextType::class, array('label'=>'Marital Status','attr' => array('class' => 'form-control')))
        ->add('nationality',TextType::class, array('label'=>'Nationality','attr' => array('class' => 'form-control')))
        ->add('userimage',FileType::class, array('label'=>' Upload Profile picture','mapped'=>false,
        'data_class'=>User::class,'required'=>false,'attr' => array('class' => 'form-control')))
        ->add('Religion',TextType::class, array('label'=>'Religion','attr' => array('class' => 'form-control')))
        ->add('deptcom',TextareaType::class, array('label'=>'Department comment','attr' => array('class' => 'form-control')))
        ->add('save',SubmitType::class, array(
            'label' => 'save',
            'attr' => array('class' => 'btn btn-primary submit-btn')
        ))
        ->getForm();

        $form->handleRequest($request);


    if ($form->isSubmitted() && $form->isValid()) {
                      // we must transform the image string from Db  to File to respect the form types
              $oldFileName = $user4->getUserimage();
                   /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['userimage']->getData();
            if ($uploadedFile) {
                $destination = $this->getParameter('photos_directory');

                $newFilename = md5(uniqid()).'.'.$uploadedFile->guessExtension();
                  try {
                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                }catch(FileException $e){
                }
                $user4->setUserimage($newFilename);
            }
            else{
                 $user4->setUserimage($oldFileName );
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirect($this->generateUrl('head'));
        }
        return $this->render('security/editstaff.html.twig',array('form'=>$form->createView()

                ));
}
   /**
     * @Route("/administrator/{id}/edit/name",name="admin")
     */
public function editadmin(Request $request,$id)
{
        $User=new User();
        $user4 =$this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $id]);

        $form = $this->createFormBuilder($user4)
        ->add('name',TextType::class, array('label'=>'Full Name','attr' => array('class' => 'form-control')))
        ->add('email',EmailType::class, array('label'=>'Email','attr' => array('class' => 'form-control')))
        ->add('gender',TextType::class, array('label'=>'Gender','attr' => array('class' => 'form-control')))
        ->add('DoB',TextType::class, array('label'=>'Date of birth','attr' => array('class' => 'form-control')))
        ->add('DoM',TextType::class, array('label'=>'Joining Date','attr' => array('class' => 'form-control')))
        ->add('title',TextType::class, array('label'=>'Title','attr' => array('class' => 'form-control')))
        ->add('phoneno',TextType::class, array('label'=>'Phone No','attr' => array('class' => 'form-control')))
        ->add('maritalstatus',TextType::class, array('label'=>'Marital Status','attr' => array('class' => 'form-control')))
        ->add('nationality',TextType::class, array('label'=>'Nationality','attr' => array('class' => 'form-control')))
        ->add('userimage',FileType::class, array('label'=>' Upload Profile picture','mapped'=>false,
        'data_class'=>User::class,'required'=>false,'attr' => array('class' => 'form-control')))
        ->add('Religion',TextType::class, array('label'=>'Religion','attr' => array('class' => 'form-control')))
        ->add('deptcom',TextareaType::class, array('label'=>'Department comment','attr' => array('class' => 'form-control')))
        ->add('save',SubmitType::class, array(
            'label' => 'save',
            'attr' => array('class' => 'btn btn-primary submit-btn')
        ))
        ->getForm();

        $form->handleRequest($request);


    if ($form->isSubmitted() && $form->isValid()) {
                      // we must transform the image string from Db  to File to respect the form types
              $oldFileName = $user4->getUserimage();
                   /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['userimage']->getData();
            if ($uploadedFile) {
                $destination = $this->getParameter('photos_directory');

                $newFilename = md5(uniqid()).'.'.$uploadedFile->guessExtension();
                  try {
                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                }catch(FileException $e){
                }
                $user4->setUserimage($newFilename);
            }
            else{
                 $user4->setUserimage($oldFileName );
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirect($this->generateUrl('adminuser'));
        }
        return $this->render('security/editstaff.html.twig',array('form'=>$form->createView()

                ));
}
        /**
     * @Route("/dashboard/available/info/{id}/edit/name",name="editlevel")
     */
public function editlevel(Request $request,$id)
 {
        $User=new User();
        $user4 =$this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $id]);

        $form = $this->createFormBuilder($user4)
        ->add('name',TextType::class, array('label'=>'Full Name','attr' => array('class' => 'form-control')))
        ->add('email',EmailType::class, array('label'=>'Email','attr' => array('class' => 'form-control')))
        ->add('gender',TextType::class, array('label'=>'Gender','attr' => array('class' => 'form-control')))
        ->add('DoB',TextType::class, array('label'=>'Date of birth','attr' => array('class' => 'form-control')))
        ->add('DoM',TextType::class, array('label'=>'Joining Date','attr' => array('class' => 'form-control')))
        ->add('title',TextType::class, array('label'=>'Title','attr' => array('class' => 'form-control')))
        ->add('phoneno',TextType::class, array('label'=>'Phone No','attr' => array('class' => 'form-control')))
        ->add('maritalstatus',TextType::class, array('label'=>'Marital Status','attr' => array('class' => 'form-control')))
        ->add('nationality',TextType::class, array('label'=>'Nationality','attr' => array('class' => 'form-control')))
        ->add('userimage',FileType::class, array('label'=>' Upload Profile picture','mapped'=>false,
        'data_class'=>User::class,'required'=>false,'attr' => array('class' => 'form-control')))
        ->add('Religion',TextType::class, array('label'=>'Religion','attr' => array('class' => 'form-control')))
        ->add('deptcom',TextareaType::class, array('label'=>'Department comment','attr' => array('class' => 'form-control')))
        ->add('save',SubmitType::class, array(
            'label' => 'save',
            'attr' => array('class' => 'btn btn-primary submit-btn')
        ))
        ->getForm();

        $form->handleRequest($request);


    if ($form->isSubmitted() && $form->isValid()) {
                      // we must transform the image string from Db  to File to respect the form types
              $oldFileName = $user4->getUserimage();
                   /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['userimage']->getData();
            if ($uploadedFile) {
                $destination = $this->getParameter('photos_directory');

                $newFilename = md5(uniqid()).'.'.$uploadedFile->guessExtension();
                  try {
                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                }catch(FileException $e){
                }
                $user4->setUserimage($newFilename);
            }
            else{
                 $user4->setUserimage($oldFileName );
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirect($this->generateUrl('dashboard'));
        }
        return $this->render('security/editstaff.html.twig',array('form'=>$form->createView()

                ));
}
     /**
     * @Route("/dashboard/profile/{id}/edit/information",name="editprofile")
     */
public function editprofile(Request $request,$id)
{
  $User=new User();
        $user4 =$this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $id]);

        $form = $this->createFormBuilder($user4)
        ->add('name',TextType::class, array('label'=>'Full Name','attr' => array('class' => 'form-control')))
        ->add('email',EmailType::class, array('label'=>'Email','attr' => array('class' => 'form-control')))
        ->add('gender',TextType::class, array('label'=>'Gender','attr' => array('class' => 'form-control')))
        ->add('DoB',TextType::class, array('label'=>'Date of birth','attr' => array('class' => 'form-control')))
        ->add('DoM',TextType::class, array('label'=>'Joining Date','attr' => array('class' => 'form-control')))
        ->add('title',TextType::class, array('label'=>'Title','attr' => array('class' => 'form-control')))
        ->add('phoneno',TextType::class, array('label'=>'Phone No','attr' => array('class' => 'form-control')))
        ->add('maritalstatus',TextType::class, array('label'=>'Marital Status','attr' => array('class' => 'form-control')))
        ->add('nationality',TextType::class, array('label'=>'Nationality','attr' => array('class' => 'form-control')))
        ->add('userimage',FileType::class, array('label'=>' Upload Profile picture','mapped'=>false,
        'data_class'=>User::class,'required'=>false,'attr' => array('class' => 'form-control')))
        ->add('Religion',TextType::class, array('label'=>'Religion','attr' => array('class' => 'form-control')))
        ->add('deptcom',TextareaType::class, array('label'=>'Department comment','attr' => array('class' => 'form-control')))
        ->add('save',SubmitType::class, array(
            'label' => 'save',
            'attr' => array('class' => 'btn btn-primary submit-btn')
        ))
        ->getForm();

        $form->handleRequest($request);


    if ($form->isSubmitted() && $form->isValid()) {
                      // we must transform the image string from Db  to File to respect the form types
              $oldFileName = $user4->getUserimage();
                   /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['userimage']->getData();
            if ($uploadedFile) {
                $destination = $this->getParameter('photos_directory');

                $newFilename = md5(uniqid()).'.'.$uploadedFile->guessExtension();
                  try {
                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                }catch(FileException $e){
                }
                $user4->setUserimage($newFilename);
            }
            else{
                 $user4->setUserimage($oldFileName );
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirect($this->generateUrl('profile'));
        }
        return $this->render('security/editstaff.html.twig',array('form'=>$form->createView()

                ));
 }



 //end of controller
}
