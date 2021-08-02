<?php

namespace App\Controller;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Promotion;
use App\Entity\Department;
use App\Entity\User;
use App\Entity\Student;
use App\Entity\Project;
use App\Entity\Themsetting;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Session\Session;

class SecurityController extends AbstractController
{
    /**
     * @Route("/{login}", name="app_login", defaults={"login":null})
     */
    public function login(AuthenticationUtils $authenticationUtils,UrlGeneratorInterface $urlGenerator): Response
    {
       $em=$this->getDoctrine()->getManager();
       $themedisplay=$this->getDoctrine()->getRepository(Themsetting::class)->findAll();
       if($this->isGranted('ROLE_ADMIN')){
             return $this->RedirectToRoute('admin');
         }
        if($this->isGranted('ROLE_STAFF')){
            return $this->RedirectToRoute('userstaff');
         }
         if($this->isGranted('ROLE_HODS')){
             return $this->RedirectToRoute('hods');
         }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('college/index.html.twig', ['themedisplay'=>$themedisplay,'last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {

      /*$currentUserId = $this->getUser()->getId();
      if ($currentUserId)
      {
        $session = $this->get('session');
        $session = new Session();
        $session->invalidate();
      }*/
      $this->get('security.token_storage')->setToken(null);
      $this->get('request')->getSession()->invalidate();
    }

 /**
     * @Route("/admin/dashboard", name="admin")
     */
    public function adminpass():Response
    {
         return $this->redirectToRoute('pass');
    }
/**
     * @Route("/hods/dashboard/page", name="hods")
     */
    public function staffpass():Response
    {
        return $this->redirectToRoute('userdashboard');
    }
    /**
     * @Route("/userstaff/dashboard/staff", name="userstaff")
     */
    public function stafftech():Response
    {
           return $this->redirectToRoute('userdashboardstaff');
    }
     /**
     * @Route("/employee/promotion/announcement", name="promotion")
     */
    public function promotion(Request $request):Response
    {
    $user1=$this->getUser();
        if($user1 != null)
        {
               $idnam=$user1->getId();
                 $em=$this->getDoctrine()->getManager();
                $promotion = new Promotion();
                $employee=$request->request->get('employee');
                $admin=$request->request->get('admin');
                $category=$request->request->get('category');
                $promo=$request->request->get('promo');
                $connection=$em->getConnection();
                $statement3=$connection->prepare("select promotion.id as ID,user.userimage as APPIMAGE,user.id as me,user.name as Emp,user.email as Email,department.name as NAME,department.id as test,promotion.promofrom as FRM,promotion.promoto as TOPROMO,promotion.promodate as PDT from user,promotion inner join promotion_user on promotion.id=promotion_id inner join department where user.department_id=department.id and user.id=user_id");
                $statement3->execute();
                $statement5=$connection->prepare("select title from user where id=:idnam");
                $statement5->bindParam(':idnam', $idnam);
                $statement5->execute();
                $statement6=$connection->prepare("select department_id as departmentID from user where id=:idnam");
                $statement6->bindParam(':idnam',$idnam);
                $statement6->execute();
                $display23=$statement6->fetchOne();
                $displaytest=$statement5->fetchOne();
                $displaypromo=$statement3->fetchAll();
               if($request->isMethod('post'))
               {
                     if(!empty($employee) & !empty($category) & !empty($admin) & !empty($promo))
                     {
                              $promofor =$this->getDoctrine()->getRepository(User::class)->findOneBy(['email'=>$employee]);
                             if($promofor)
                             {
                                   if ($displaytest=='HoDs' & $display23)
                                    {
                                       $promotion->addPid($promofor);
                                    }
                                   else if($displaytest=='administrator')
                                    {
                                       $promotion->addPid($promofor);
                                    }
                                   else
                                      {
                                        $this->addFlash('said',$employee.' '.'this email not belong to you department');
                                     return $this->render('college/promotion.html.twig',['display23'=>$display23,'displaypromo'=>$displaypromo,'displaytest'=>$displaytest]);
                                      }
                                    $promotion->setPromoto($category);
                                    $promotion->setPromofrom($admin);
                                    $promotion->setPromodate($promo);
                                    $em->persist($promotion);
                                    $em->flush();
                                    $this->addFlash('promoted',  '');
                                     return $this->redirectToRoute('promotion');
                             }
                            else
                            {
                                    $this->addFlash('promoted',  'User email not exists');
                                    return $this->render('college/promotion.html.twig',['display23'=>$display23,'displaypromo'=>$displaypromo,'displaytest'=>$displaytest]);
                            }
                      }
                      else
                      {
                                     $this->addFlash('promoted',  'All fields are required');
                                    return $this->render('college/promotion.html.twig',['display23'=>$display23,'displaypromo'=>$displaypromo,'displaytest'=>$displaytest]);
                      }
                    }

                    $this->addFlash('promoted',  '');
                    return $this->render('college/promotion.html.twig',['display23'=>$display23,'displaypromo'=>$displaypromo,'displaytest'=>$displaytest]);
        }
        else
        {
            $success="You must sign in again to access you account";
            return $this->render('college/404.html.twig',['success'=>$success]);
        }
     }

   /**
     * @Route("/user/define/print/pdf", name="pdf")
     */
public function generate_pdf(){

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('college/pdf.html.twig', [
            'headline' => "List of Undergraduate Students"
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("undergraduated.pdf", [
            "Attachment" => true
        ]);
        return new Response("The PDF file has been succesfully generated !");
    }
     /**
     * @Route("/security/logs/define/print/pdf", name="logspdf")
     */
public function logs_pdf(){

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $em=$this->getDoctrine()->getManager();
        $connection=$em->getConnection();
        $statement3=$connection->prepare("select user.userimage as APPIMAGE,user.id as ID,user.name,user.email,last_activity_at from user  where last_activity_at is not null order by last_activity_at desc");
        $statement3->execute();
        $displaystaff=$statement3->fetchAll();

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('college/logspdf.html.twig', [
            'headline' => "Security Logs Day Report"
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("logs.pdf", [
            "Attachment" => true
        ]);
        return new Response("The PDF file has been succesfully generated !");
    }


   /**
     * @Route("/user/print/pdf", name="postpdf")
     */
public function set_pdf(){

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('college/postpdf.html.twig', [
            'headline' => "List of Postgraduate Students"
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("postgraduated.pdf", [
            "Attachment" => true
        ]);
        return new Response("The PDF file has been succesfully generated !");
    }
  /**
     * @Route("/user/print/staff/pdf", name="staffpdf")
     */
public function pdf(){

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('college/staffpdf.html.twig', [
            'headline' => "List of ALL Staff"
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("postgraduated.pdf", [
            "Attachment" => true
        ]);
        return new Response("The PDF file has been succesfully generated !");
    }

/**
     * @Route("/hods/dashboard/department", name="userdashboard")
     */
    public function userdashboard(Request $request): Response
    {
             $user1=$this->getUser();
        if($user1 != null){
                $username=$this->getUser()->getName();
                $idnam=$user1->getId();
               //$ip=$request->request->getClientIp();
               $em=$this->getDoctrine()->getManager();
                $connection=$em->getConnection();
                $statement3=$connection->prepare("select department_id as departmentID from user where id=:idnam");
                $statement3->bindParam(':idnam',$idnam);
                $statement3->execute();
                $rise=$statement3->fetchOne();
                $statement645=$connection->prepare("select count(*) from programme where programmedept_id=:rise");
                $statement645->bindParam(':rise',$rise);
                $statement645->execute();
                $statement647=$connection->prepare("select count(*) from course");
                $statement647->execute();
                $prog645=$statement645->fetchOne();
                $prog647=$statement647->fetchOne();
                $statement4=$connection->prepare("select count(*) from student where level='undergraduate' and department_id='".$rise."' ");
                $statement4->execute();
                $statement5=$connection->prepare("select count(*) from student where level='postgraduate' and department_id='".$rise."'");
                $statement5->execute();
                $statement51=$connection->prepare("select count(*) from project_department where department_id='".$rise."'");
                $statement51->execute();
                $statement52=$connection->prepare("select count(*) from user where title='staff' and department_id='".$rise."'");
                $statement52->execute();
                $statement61=$connection->prepare("select name from user where title='staff' and department_id='".$rise."' limit 4");
                $statement61->execute();
                $statement7=$connection->prepare("select project.id,department.name as department,department.id as ID,project.name as project from project,department inner join project_department on department_id='".$rise."' where project_id=project.id limit 4");
                $statement7->execute();
                $display=$statement7->fetchAll();
                $statement23=$connection->prepare("select name from department where id=:rise");
                $statement23->bindParam(':rise',$rise);
                $statement23->execute();
                $statement6=$connection->prepare("select department_id as departmentID from user where id=:idnam");
                $statement6->bindParam(':idnam',$idnam);
                $statement6->execute();
                $today=new \DateTime("now");
                $today=$today->format('Y-m-d');
                $statement13=$connection->prepare("select count(*) from user inner join leaves inner join leaves_user on leaves.id=leaves_id inner join department where department.id='".$rise."' and user.id=user_id and (title='HoDs' or title='staff') and status='pending' and approveddate='".$today."'");
                $statement13->execute();
                $statement12=$connection->prepare("select count(*) from user inner join leaves inner join leaves_user on leaves.id=leaves_id inner join department where department.id='".$rise."'  and user.id=user_id and (title='HoDs' or title='staff') and status='approved' and approveddate='".$today."'");
                $statement12->execute();
                $statement34=$connection->prepare("select leaves.approvedimage as APPIMAGE,user.name as Emp,leaves.lfrom as FRM,leaves.status as ST from user inner join leaves inner join leaves_user on leaves.id=leaves_id inner join department where department.id='2' and user.id=user_id and (title='HoDs' or title='staff') and  (status='approved' or status='pending') and approveddate='".$today."' limit 2");
                $statement34->execute();
                $display124=$statement34->fetchAll();
                $display23=$statement6->fetchOne();
                $display24=$statement23->fetchOne();
                $displaystaff=$statement61->fetchAll();
                $findstudent=$statement5->fetchOne();
                $findproject=$statement51->fetchOne();
                $findlist=$statement52->fetchOne();
                $findstaff=$statement4->fetchOne();
                $absent=$statement12->fetchOne();
                $pending=$statement13->fetchOne();
                 return $this->render('college/userdashboard.html.twig',['prog647'=>$prog647,'prog645'=>$prog645,'absent'=>$absent,'pending'=>$pending,'display124'=>$display124,'username'=>$username,'findlist'=>$findlist,'findstudent'=>$findstudent,'findproject'=>$findproject,'display'=>$display,'displaystaff'=>$displaystaff,'display23'=>$display23,'display24'=>$display24,'findstaff'=>$findstaff]);
        }
        else
        {
                $success="You must sign in again to access you account";
                return $this->render('college/404.html.twig',['success'=>$success]);

        }
     }
     /**
     * @Route("/member/staff/dashboard/department/page", name="userdashboardstaff")
     */
    public function userdashboardstaff(Request $request): Response
    {
        $user1=$this->getUser();
        if($user1 != null){
                $username=$this->getUser()->getName();
                $idnam=$user1->getId();
               //$ip=$request->request->getClientIp();
               $em=$this->getDoctrine()->getManager();
                $connection=$em->getConnection();
                $statement3=$connection->prepare("select department_id as departmentID from user where id=:idnam");
                $statement3->bindParam(':idnam',$idnam);
                $statement3->execute();
                $rise=$statement3->fetchOne();
               $findusertest=$connection->prepare("select id,coursename,credits,cshortname from course inner join course_user on id=course_id where user_id=:usertest");
               $findusertest->bindParam(':usertest',$idnam);
               $findusertest->execute();
               $findusertest=$findusertest->fetchAll();
               $findprog=$connection->prepare(" select count(*) from programme inner join course_programme  on id=programme_id where  programmedept_id=:test");
               $findprog->bindParam(':test',$rise);
               $findprog->execute();
               $findprog=$findprog->fetchOne();
                $statement4=$connection->prepare("select count(*) from student where level='undergraduate' and department_id='".$rise."' ");
                $statement4->execute();
                $statement5=$connection->prepare("select count(*) from student where level='postgraduate' and department_id='".$rise."'");
                $statement5->execute();
                $statement51=$connection->prepare("select count(*) from project_department where department_id='".$rise."'");
                $statement51->execute();
                $statement6=$connection->prepare("select department.name as department,user.name,user.title,user.deptcom from user inner join department on user.department_id= department.id limit 4");
                $statement6->execute();
                $statement7=$connection->prepare("select project.id,department.name as department,department.id as ID,project.name as project from project,department inner join project_department on department_id=department.id where project_id=project.id limit 4");
                $statement7->execute();
                $display=$statement7->fetchAll();
                $statement23=$connection->prepare("select name from department where id=:rise");
                $statement23->bindParam(':rise',$rise);
                $statement23->execute();
                $statement6=$connection->prepare("select department_id as departmentID from user where id=:idnam");
                $statement6->bindParam(':idnam',$idnam);
                $statement6->execute();
                $statement61=$connection->prepare("select avg(lchance-lrem) from leaves_type");
                $statement61->execute();
                $statement64=$connection->prepare("select * from leaves_type");
                $statement64->execute();
                $statement62=$connection->prepare("select sum(lrem) from leaves_type");
                $statement62->execute();
                $statement63=$connection->prepare("select count(*) from project_user where user_id=:idnam");
                $statement63->bindParam(':idnam',$idnam);
                $statement63->execute();
                $today=new \DateTime("now");
                $today=$today->format('Y-m-d');
                $statement13=$connection->prepare("select count(*) from leaves inner join leaves_user on leaves.id=leaves_id where user_id='".$idnam."' and status='pending' and approveddate='".$today."'");
                $statement13->execute();
                $statement12=$connection->prepare(" select count(*) from leaves inner join leaves_user on leaves.id=leaves_id where user_id='".$idnam."' and status='approved' and approveddate='".$today."'");
                $statement12->execute();
                $statement34=$connection->prepare("select leaves.approvedimage as APPIMAGE,leaves.lreason as lreason,user.name as Emp,leaves.lfrom as FRM,leaves.status as ST,leaves_type.name as ltype from user inner join leaves inner join leaves_user on leaves.id=leaves_id inner join department inner join leaves_type inner join leaves_leaves_type on leaves_type.id=leaves_type_id where user.department_id=department.id and user.id=26 and user.title='staff' and leaves.id=leaves_leaves_type.leaves_id and (status='approved' or status='pending') and approveddate='".$today."' limit 2 ;");
                $statement34->execute();
                $display124=$statement34->fetchAll();
                $display23=$statement6->fetchOne();
                $display24=$statement23->fetchOne();
                $displaystaff=$statement6->fetchAll();
                $findstudent=$statement5->fetchOne();
                $findproject=$statement51->fetchOne();
                $findstaff=$statement4->fetchOne();
                $absent=$statement12->fetchOne();
                $pending=$statement13->fetchOne();
                $avgleaves=$statement61->fetchOne();
                $remain=$statement62->fetchOne();
                $assign=$statement63->fetchOne();
                $assign2=$statement64->fetchOne();
                return $this->render('college/userdashboardstaff.html.twig',['findprog'=>$findprog,'findusertest'=>$findusertest,'avgleaves'=>$avgleaves,'remain'=>$remain,'assign'=>$assign,'assign2'=>$assign2,'absent'=>$absent,'pending'=>$pending,'display124'=>$display124,'username'=>$username,'findstudent'=>$findstudent,'findproject'=>$findproject,'display'=>$display,'displaystaff'=>$displaystaff,'display23'=>$display23,'display24'=>$display24,'findstaff'=>$findstaff]);
        }
        else
        {
                $success="You must sign in again to access you account";
                return $this->render('college/404.html.twig',['success'=>$success]);

        }
     }

         /**
     * @Route("/security/view/logs/info", name="logs")
     */
public function logs(): Response
 {

 $user1=$this->getUser();
 if($user1 != null)
    {
        $idnam=$user1->getId();
        $em=$this->getDoctrine()->getManager();
        $connection=$em->getConnection();
        $statement3=$connection->prepare("select user.userimage as APPIMAGE,user.id as ID,user.name,user.email,last_activity_at from user  where last_activity_at is not null order by last_activity_at desc");
        $statement3->execute();
        $displaystaff=$statement3->fetchAll();
       
            return $this->render('college/logs.html.twig',['displaystaff'=>$displaystaff]);
    }
    else
    {
            $success="You must sign in again to access you account";
            return $this->render('college/404.html.twig',['success'=>$success]);
    }
}

}
