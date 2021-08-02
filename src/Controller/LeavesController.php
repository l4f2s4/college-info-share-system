<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Leaves;
use App\Entity\LeavesType;
use App\Entity\Project;
use App\Entity\Department;
use App\Entity\User;

class LeavesController extends AbstractController
{
    /**
     * @Route("/leaves", name="leaves")
     */
    public function index(Request $request): Response
    {
     $user1=$this->getUser();
        if($user1 != null)
        {
                $idnam=$user1->getId();
                $em=$this->getDoctrine()->getManager();
                $leaves = new Leaves();
                $findleaves =$this->getDoctrine()->getRepository(LeavesType::class)->findBy(['lstatus'=>'active']);
                $employee=$this->getUser()->getEmail();
                $employee=$this->getDoctrine()->getRepository(User::class)->findOneBy(['email'=>$employee]);
                $ltype=$request->request->get('ltype');
                $lfrom=$request->request->get('lfrom');
                $lto=$request->request->get('lto');
                $lreason=$request->request->get('lreason');
                $connection=$em->getConnection();
                $statement3=$connection->prepare("select leaves.approvedimage,user.userimage,leaves.id as lID,user.name as Emp,user.id as ID,user.title as title,department.name as NAME,leaves.lfrom as FRM,leaves_type.id as moment,leaves.lto as lTO,leaves_type.ldays as lDay,leaves_type.name as ltype,leaves.lreason as lreason,leaves.status as status from user,leaves inner join leaves_user on leaves.id=leaves_id inner join department inner join leaves_type inner join leaves_leaves_type on leaves_type.id=leaves_type_id where user.department_id=department.id and user.id=user_id or leaves.id=leaves_leaves_type.leaves_id");
                $statement3->execute();
                $statement5=$connection->prepare("select title from user where id=:idnam");
                $statement5->bindParam(':idnam', $idnam);
                $statement5->execute();
                $statement6=$connection->prepare("select department_id as departmentID from user where id=:idnam");
                $statement6->bindParam(':idnam',$idnam);
                $statement6->execute();
                $statement7=$connection->prepare("select count(*) from user inner join leaves inner join leaves_user on leaves.id=leaves_id where user.id=user_id and title='administrator'");
                $statement7->execute();
                $statement9=$connection->prepare("select count(*) from user inner join leaves inner join leaves_user on leaves.id=leaves_id where user.id=user_id and title='administrator' and status='new'");
                $statement9->execute();
                $statement10=$connection->prepare("select count(*) from user inner join leaves inner join leaves_user on leaves.id=leaves_id where user.id=user_id and title='administrator' and status='decline'");
                $statement10->execute();
                $statement11=$connection->prepare("select count(*) from user inner join leaves inner join leaves_user on leaves.id=leaves_id where user.id=user_id and title='administrator' and status='pending'");
                $statement11->execute();
                $statement12=$connection->prepare("select count(*) from user inner join leaves inner join leaves_user on leaves.id=leaves_id where user.id=user_id and title='administrator' and status='approved'");
                $statement12->execute();
                $display23=$statement6->fetchOne();
                $total=$statement7->fetchOne();
                $totalnew=$statement9->fetchOne();
                $totaldecline=$statement10->fetchOne();
                $totalpending=$statement11->fetchOne();
                $totalapproved=$statement12->fetchOne();
                $displaytest=$statement5->fetchOne();
                $displayleaves=$statement3->fetchAll();
               if($request->isMethod('post'))
               {
                     if(!empty($ltype) & !empty($lfrom) & !empty($lto) & !empty($lreason)){

                                   $ltype=$this->getDoctrine()->getRepository(LeavesType::class)->findOneBy(['name' => $ltype]);
                                    $leaves->setLto(new \DateTime($lto));
                                    $leaves->setLfrom(new \DateTime($lfrom));
                                    $leaves->setLreason($lreason);
                                    $leaves->setStatus('new');
                                    $leaves->addUpdatetype($ltype);
                                    $leaves->addLemp($employee);
                                    $em->persist($leaves);
                                    $em->flush();
                                    $this->addFlash('love',  '');
                                     return $this->redirectToRoute('leaves');

                      }
                      else
                      {
                                     $this->addFlash('love',  'All fields are required');
                                     return $this->render('leaves/index.html.twig',['findleaves'=>$findleaves,'totalapproved'=>$totalapproved,'totaldecline'=>$totaldecline,'totalpending'=>$totalpending,'totalnew'=>$totalnew,'total'=>$total,'display23'=>$display23,'displayleaves'=>$displayleaves,'displaytest'=>$displaytest]);
                      }
                    }

                    $this->addFlash('love',  '');
                    return $this->render('leaves/index.html.twig',['findleaves'=>$findleaves,'totalapproved'=>$totalapproved,'totaldecline'=>$totaldecline,'totalpending'=>$totalpending,'totalnew'=>$totalnew,'total'=>$total,'display23'=>$display23,'displayleaves'=>$displayleaves,'displaytest'=>$displaytest]);
        }
        else
        {
            $success="You must sign in again to access you account";
            return $this->render('college/404.html.twig',['success'=>$success]);
        }
    }
      /**
     * @Route("/leaves/employee", name="leavesemployee")
     */
    public function leavesemployee(Request $request): Response
    {
     $user1=$this->getUser();
        if($user1 != null)
        {
                 $idnam=$user1->getId();
                $em=$this->getDoctrine()->getManager();
                $connection=$em->getConnection();
                $leaves = new Leaves();
                $findleaves =$this->getDoctrine()->getRepository(LeavesType::class)->findBy(['lstatus'=>'active']);
                $employee=$this->getUser()->getEmail();
                $employee=$this->getDoctrine()->getRepository(User::class)->findOneBy(['email'=>$employee]);
                $lafesa=$connection->prepare("select * from leaves_type where lstatus='active' limit 2");;
                $lafesa->execute();
                $ltype=$request->request->get('ltype');
                $lfrom=$request->request->get('lfrom');
                $lto=$request->request->get('lto');
                $lreason=$request->request->get('lreason');
                $statement3=$connection->prepare("select leaves.approvedimage as APPIMAGE,user.userimage,leaves.id as lID,leaves.approvedby as APP,user.name as Emp,user.id as ID,user.title as title,department.name as NAME,department.id as just,leaves.lfrom as FRM,leaves.lto as lTO,leaves_type.ldays as lDay,leaves_type.name as ltype,leaves_type.id as say,leaves_type.lrem as Remain,leaves_type.lchance as Available,leaves.lreason as lreason,leaves.status as status from user,leaves inner join leaves_user on leaves.id=leaves_id inner join department inner join leaves_type inner join leaves_leaves_type on leaves_type.id=leaves_type_id where user.department_id=department.id and user.id=user_id or leaves.id=leaves_leaves_type.leaves_id");
                $statement3->execute();
                $statement5=$connection->prepare("select title from user where id=:idnam");
                $statement5->bindParam(':idnam', $idnam);
                $statement5->execute();
                $statement6=$connection->prepare("select department_id from user where id=:idnam");
                $statement6->bindParam(':idnam',$idnam);
                $statement6->execute();
                $statement7=$connection->prepare("select count(*) from user inner join leaves inner join leaves_user on leaves.id=leaves_id where user.id=user_id and (title='HoDs' or title='staff')");
                $statement7->execute();
                $statement9=$connection->prepare("select count(*) from user inner join leaves inner join leaves_user on leaves.id=leaves_id where user.id=user_id and (title='HoDs' or title='staff') and status='new'");
                $statement9->execute();
                $statement10=$connection->prepare("select count(*) from user inner join leaves inner join leaves_user on leaves.id=leaves_id where user.id=user_id and (title='HoDs' or title='staff') and status='decline'");
                $statement10->execute();
                $statement11=$connection->prepare("select count(*) from user inner join leaves inner join leaves_user on leaves.id=leaves_id where user.id=user_id and (title='HoDs' or title='staff') and status='pending'");
                $statement11->execute();
                $statement12=$connection->prepare(" select count(*) from user inner join leaves inner join leaves_user on leaves.id=leaves_id where user.id=user_id and (title='HoDs' or title='staff') and status='approved'");
                $statement12->execute();
                $display23=$statement6->fetchOne();
                $statement71=$connection->prepare("select count(*) from user inner join leaves inner join leaves_user on leaves.id=leaves_id inner join department where department.id='".$display23."' and user.id=user_id and (title='HoDs' or title='staff')");
                $statement71->execute();
                $statement91=$connection->prepare("select count(*) from user inner join leaves inner join leaves_user on leaves.id=leaves_id inner join department where department.id='".$display23."' and user.id=user_id and (title='HoDs' or title='staff') and status='new'");
                $statement91->execute();
                $statement101=$connection->prepare("select count(*) from user inner join leaves inner join leaves_user on leaves.id=leaves_id inner join department where department.id='".$display23."' and user.id=user_id and (title='HoDs' or title='staff') and status='decline'");
                $statement101->execute();
                $statement111=$connection->prepare("select count(*) from user inner join leaves inner join leaves_user on leaves.id=leaves_id inner join department where department.id='".$display23."' and user.id=user_id and (title='HoDs' or title='staff') and status='pending'");
                $statement111->execute();
                $statement121=$connection->prepare("select count(*) from user inner join leaves inner join leaves_user on leaves.id=leaves_id inner join department where department.id='".$display23."' and user.id=user_id and (title='HoDs' or title='staff') and status='approved'");
                $statement121->execute();
                $statementC=$connection->prepare("select count(*) from leaves_type");
                $statementC->execute();
                $cout=$statementC->fetchOne();
                $total=$statement7->fetchOne();
                $lafesa=$lafesa->fetchAll();
                $totalnew=$statement9->fetchOne();
                $totaldecline=$statement10->fetchOne();
                $totalpending=$statement11->fetchOne();
                $totalapproved=$statement12->fetchOne();
                $totalhod=$statement71->fetchOne();
                $totalnewhod=$statement91->fetchOne();
                $totaldeclinehod=$statement101->fetchOne();
                $totalpendinghod=$statement111->fetchOne();
                $totalapprovedhod=$statement121->fetchOne();
                $displaytest=$statement5->fetchOne();
                $displayleaves=$statement3->fetchAll();
               if($request->isMethod('post'))
               {
                     if(!empty($ltype) & !empty($lfrom) & !empty($lto) & !empty($lreason))
                     {
                                    $ltype=$this->getDoctrine()->getRepository(LeavesType::class)->findOneBy(['name' => $ltype]);
                                    $leaves->setLto(new \DateTime($lto));
                                    $leaves->setLfrom(new \DateTime($lfrom));
                                    $leaves->setLreason($lreason);
                                    $leaves->setStatus('new');
                                    $leaves->addUpdatetype($ltype);
                                    $leaves->addLemp($employee);
                                    $em->persist($leaves);
                                    $em->flush();
                                    $this->addFlash('typ',  '');
                                     return $this->redirectToRoute('leavesemployee');

                      }
                      else
                      {
                                     $this->addFlash('typ',  'All fields are required');
                                     return $this->render('leaves/leaves.html.twig',['findleaves'=>$findleaves,'totalapproved'=>$totalapproved,'totaldecline'=>$totaldecline,'totalpending'=>$totalpending,'totalnew'=>$totalnew,'total'=>$total
                                     ,'totalapprovedhod'=>$totalapprovedhod,'totaldeclinehod'=>$totaldeclinehod,'totalpendinghod'=>$totalpendinghod,'totalnewhod'=>$totalnewhod,'totalhod'=>$totalhod,
                                     'cout'=>$cout,'lafesa'=>$lafesa,'display23'=>$display23,'displayleaves'=>$displayleaves,'displaytest'=>$displaytest]);
                      }
                    }

                    $this->addFlash('typ',  '');
                    return $this->render('leaves/leaves.html.twig',['findleaves'=>$findleaves,'totalapproved'=>$totalapproved,'totaldecline'=>$totaldecline,'totalpending'=>$totalpending,'totalnew'=>$totalnew,'total'=>$total,
                    'totalapprovedhod'=>$totalapprovedhod,'totaldeclinehod'=>$totaldeclinehod,'totalpendinghod'=>$totalpendinghod,'totalnewhod'=>$totalnewhod,'totalhod'=>$totalhod,
                    'cout'=>$cout,'lafesa'=>$lafesa,'display23'=>$display23,'displayleaves'=>$displayleaves,'displaytest'=>$displaytest]);
        }
        else
        {
            $success="You must sign in again to access you account";
            return $this->render('college/404.html.twig',['success'=>$success]);
        }
    }
      /**
        *remove leaves request
        *
     * @Route("/remove/request/{id}/{test}/{legend}", name="removerequest")
     */
    public function removerequest($id,$test,$legend): Response
    {
             $user1=$this->getUser();

                $em=$this->getDoctrine()->getManager();
                if($user1!=null){
                       $leaves =$this->getDoctrine()->getRepository(Leaves::class)->findOneBy(['id' => $id]);
                       $leaveuser =$this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $test]);
                       $leavestypr =$this->getDoctrine()->getRepository(LeavesType::class)->findOneBy(['id' => $legend]);
                       $rem=$leavestypr->getLrem();
                       $chan=$leavestypr->getLchance();
                if($leaves){
                        if($rem<$chan){
                        $leaves->removeLemp($leaveuser);
                        $leavestypr->setLrem($rem+1);
                        $em->persist($leavestypr);
                        $em->remove($leaves);
                        $em->flush();
                       return $this->redirectToRoute('leaves');
                           }
                        $leaves->removeLemp($leaveuser);
                        $em->remove($leaves);
                        $em->flush();
                        return $this->redirectToRoute('leaves');
                }
                else{
                          $success="Permission revoke..please contact your administrator for more info";
                          return $this->render('college/accessdenied.html.twig',['success'=>$success]);
                   }
    }
    }
     /**
        *approved leaves request
        *
     * @Route("/leaves/approved/request/{id}/{moment}", name="approvedrequest")
     */
    public function approvedrequest($id,$moment): Response
    {
               $user1=$this->getUser();

                $em=$this->getDoctrine()->getManager();
                if($user1!=null)
                {
                $userimage=$user1->getUserimage();
                         $leaves =$this->getDoctrine()->getRepository(Leaves::class)->findOneBy(['id' => $id]);
                         $leavestyp =$this->getDoctrine()->getRepository(LeavesType::class)->findOneBy(['id' => $moment]);
                if($leaves)
                {
                        $rem=$leavestyp->getLrem();
                        if($rem >=1){
                            $userprove=$this->getUser()->getName();
                            $today=new \DateTime('now');
                            $today=$today->format('Y-m-d');
                            $leaves->setApproveddate($today);
                            $leaves->setStatus('approved');
                            $leaves->setApprovedby($userprove);
                            $leaves->setApprovedimage($userimage);
                            $leavestyp->setLrem($rem-1);
                            $em->persist($leavestyp);
                            $em->persist($leaves);
                            $em->flush();
                            return $this->redirectToRoute('leaves');
                        }
                          $userprove=$this->getUser()->getName();
                            $today=new \DateTime('now');
                            $today=$today->format('Y-m-d');
                            $leaves->setApproveddate($today);
                            $leaves->setStatus('approved');
                            $leaves->setApprovedby($userprove);
                            $leaves->setApprovedimage($userimage);
                            $em->persist($leaves);
                            $em->flush();
                            return $this->redirectToRoute('leaves');

                }

                }
                else{
                          $success="Permission revoke..please contact your administrator for more info";
                          return $this->render('college/accessdenied.html.twig',['success'=>$success]);
                   }
    }
      /**
        *decline leaves request
        *
     * @Route("/leaves/decline/{id}", name="declinerequest")
     */
    public function declinerequest($id): Response
    {
               $user1=$this->getUser();

                $em=$this->getDoctrine()->getManager();
                if($user1!=null)
                {
                        $leaves =$this->getDoctrine()->getRepository(Leaves::class)->findOneBy(['id' => $id]);
                if($leaves)
                {
                        $userprove=$this->getUser()->getName();
                        $leaves->setStatus('decline');
                        $em->persist($leaves);
                        $em->flush();
                       return $this->redirectToRoute('leaves');
                }

                }
                else{
                          $success="Permission revoke..please contact your administrator for more info";
                          return $this->render('college/accessdenied.html.twig',['success'=>$success]);
                   }
    }
      /**
        *pending leaves request
        *
     * @Route("/leaves/pending/user/request/{id}", name="pendingrequest")
     */
    public function pendingrequest($id): Response
    {
               $user1=$this->getUser();

                $em=$this->getDoctrine()->getManager();
                if($user1!=null)
                {
                $userimage=$user1->getUserimage();
                        $leaves =$this->getDoctrine()->getRepository(Leaves::class)->findOneBy(['id' => $id]);
                if($leaves)
                {
                        $userprove=$this->getUser()->getName();
                        $leaves->setStatus('pending');
                         $today=new \DateTime('now');
                        $today=$today->format('Y-m-d');
                        $leaves->setApproveddate($today);
                        $leaves->setApprovedby($userprove);
                        $leaves->setApprovedimage($userimage);
                        $em->persist($leaves);
                        $em->flush();
                       return $this->redirectToRoute('leaves');
                }

                }
                else{
                          $success="Permission revoke..please contact your administrator for more info";
                          return $this->render('college/accessdenied.html.twig',['success'=>$success]);
                   }
    }

/**
*for leaves employee
*
*/

 /**
        *remove leaves employee request
        *
     * @Route("/remove/employee/available/request/{id}/{test}/{includ}", name="removerequestemployee")
     */
    public function removeemployee($id,$test,$includ): Response
    {
               $user1=$this->getUser();

                $em=$this->getDoctrine()->getManager();
                if($user1!=null){
                       $leaves =$this->getDoctrine()->getRepository(Leaves::class)->findOneBy(['id' => $id]);
                       $leaveuser =$this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $test]);
                       $leavestypr =$this->getDoctrine()->getRepository(LeavesType::class)->findOneBy(['id' => $includ]);
                       $rem=$leavestypr->getLrem();
                       $chan=$leavestypr->getLchance();
                if($leaves){
                        if($rem<$chan){
                        $leaves->removeLemp($leaveuser);
                        $leavestypr->setLrem($rem+1);
                        $em->persist($leavestypr);
                        $em->remove($leaves);
                        $em->flush();
                       return $this->redirectToRoute('leavesemployee');
                           }
                        $leaves->removeLemp($leaveuser);
                        $em->remove($leaves);
                        $em->flush();
                        return $this->redirectToRoute('leavesemployee');
                }
                else{
                          $success="Permission revoke..please contact your administrator for more info";
                          return $this->render('college/accessdenied.html.twig',['success'=>$success]);
                   }
    }
    }
     /**
        *approved leaves employee request
        *
     * @Route("/leaves/employee/available/approved/request/{id}/{say}", name="approvedrequestemployee")
     */
    public function approvedemployee($id,$say): Response
    {
               $user1=$this->getUser();

                $em=$this->getDoctrine()->getManager();
                if($user1!=null)
                {
                $userimage=$user1->getUserimage();
                        $leaves =$this->getDoctrine()->getRepository(Leaves::class)->findOneBy(['id' => $id]);
                         $leavestyp =$this->getDoctrine()->getRepository(LeavesType::class)->findOneBy(['id' => $say]);
                if($leaves)
                {
                        $rem=$leavestyp->getLrem();
                        if($rem >=1){
                            $userprove=$this->getUser()->getName();
                            $today=new \DateTime('now');
                            $today=$today->format('Y-m-d');
                            $leaves->setApproveddate($today);
                            $leaves->setStatus('approved');
                            $leaves->setApprovedby($userprove);
                            $leaves->setApprovedimage($userimage);
                            $leavestyp->setLrem($rem-1);
                            $em->persist($leavestyp);
                            $em->persist($leaves);
                            $em->flush();
                            return $this->redirectToRoute('leavesemployee');
                        }
                          $userprove=$this->getUser()->getName();
                            $today=new \DateTime('now');
                            $today=$today->format('Y-m-d');
                            $leaves->setApproveddate($today);
                            $leaves->setStatus('approved');
                            $leaves->setApprovedby($userprove);
                            $leaves->setApprovedimage($userimage);
                            $em->persist($leaves);
                            $em->flush();
                            return $this->redirectToRoute('leavesemployee');

                }

                }
                else{
                          $success="Permission revoke..please contact your administrator for more info";
                          return $this->render('college/accessdenied.html.twig',['success'=>$success]);
                   }
    }
      /**
        *decline leaves employee request
        *
     * @Route("/leaves/employee/available/decline/{id}", name="declinerequestemployee")
     */
    public function declineemployee($id): Response
    {
               $user1=$this->getUser();

                $em=$this->getDoctrine()->getManager();
                if($user1!=null)
                {
                        $leaves =$this->getDoctrine()->getRepository(Leaves::class)->findOneBy(['id' => $id]);
                if($leaves)
                {
                        $userprove=$this->getUser()->getName();
                        $leaves->setStatus('decline');
                        $em->persist($leaves);
                        $em->flush();
                       return $this->redirectToRoute('leavesemployee');
                }

                }
                else{
                          $success="Permission revoke..please contact your administrator for more info";
                          return $this->render('college/accessdenied.html.twig',['success'=>$success]);
                   }
    }
      /**
        *pending leaves employee request
        *
     * @Route("/leaves/employee/available/pending/user/request/{id}", name="pendingrequestemployee")
     */
    public function pendingemployee($id): Response
    {
               $user1=$this->getUser();

                $em=$this->getDoctrine()->getManager();
                if($user1!=null)
                {
                  $userimage=$user1->getUserimage();
                        $leaves =$this->getDoctrine()->getRepository(Leaves::class)->findOneBy(['id' => $id]);

                if($leaves)
                {
                        $userprove=$this->getUser()->getName();
                        $leaves->setStatus('pending');
                        $today=new \DateTime('now');
                        $today=$today->format('Y-m-d');
                        $leaves->setApproveddate($today);
                        $leaves->setApprovedby($userprove);
                        $leaves->setApprovedimage($userimage);
                        $em->persist($leaves);
                        $em->flush();
                       return $this->redirectToRoute('leavesemployee');
                }

                }
                else{
                          $success="Permission revoke..please contact your administrator for more info";
                          return $this->render('college/accessdenied.html.twig',['success'=>$success]);
                   }
    }





     /**
     * @Route("/leaves/settings", name="leavessetting")
     */
    public function settings(Request $request): Response
    {
             $user1=$this->getUser();
        if($user1 != null)
        {

                $findleaves =$this->getDoctrine()->getRepository(LeavesType::class)->findAll();
                return $this->render('leaves/leavessetting.html.twig',['findleavestype'=>$findleaves]);
        }
        else
        {
            $success="You must sign in again to access you account";
            return $this->render('college/404.html.twig',['success'=>$success]);
        }
    }

/**
     * @Route("/leaves/settings/switch/{id}", name="settingswitch")
     */
    public function settingsswitch($id): Response
    {
             $user1=$this->getUser();
        if($user1 != null)
        {
                $idnam=$user1->getId();
                $em=$this->getDoctrine()->getManager();
                $findleaves =$this->getDoctrine()->getRepository(LeavesType::class)->findOneBy(['id'=>$id]);
                    $findleaves->setLstatus('active');
                     $em->persist($findleaves);
                     $em->flush($findleaves);
                    return $this->redirectToRoute('leavestype');
        }
        else
        {
            $success="You must sign in again to access you account";
            return $this->render('college/404.html.twig',['success'=>$success]);
        }
    }

/**
     * @Route("/leaves/settings/switch/off/{id}", name="settingswitchoff")
     */
    public function settingsswitchoff($id): Response
    {
             $user1=$this->getUser();
        if($user1 != null)
        {
                $idnam=$user1->getId();
                $em=$this->getDoctrine()->getManager();
                $findleaves =$this->getDoctrine()->getRepository(LeavesType::class)->findOneBy(['id'=>$id]);
                     $findleaves->setLstatus('inactive');
                     $em->persist($findleaves);
                     $em->flush($findleaves);
                    return $this->redirectToRoute('leavestype');
        }
        else
        {
            $success="You must sign in again to access you account";
            return $this->render('college/404.html.twig',['success'=>$success]);
        }
    }





     /**
     * @Route("/leaves/types/set", name="leavestype")
     */
    public function leavestype(Request $request): Response
    {
             $user1=$this->getUser();
        if($user1 != null )
        {
           $findleaves =$this->getDoctrine()->getRepository(LeavesType::class)->findAll();
            $em=$this->getDoctrine()->getManager();
            $leavestype =new LeavesType();
            $lname=$request->request->get('lname');
            $lday=$request->request->get('lday');
            $lchance=$request->request->get('lchance');
            if($request->isMethod('post'))
            {
              if(!empty($lname) & !empty($lday) & !empty($lchance))
               {
                    $leavestype->setName($lname);
                    $leavestype->setLdays($lday);
                    $leavestype->setLchance($lchance);
                    $leavestype->setLrem($lchance);
                    $leavestype->setLstatus('inactive');
                    $em->persist($leavestype);
                    $em->flush();
                    return $this->redirectToRoute('leavestype');
                }
               else
                {
                 $this->addFlash('leaves',  'This field is required!..');
                 return $this->render('leaves/leavestype.html.twig',['findleavestype'=>$findleaves]);
                }
                  $this->addFlash('leaves',  '');
                  return $this->render('leaves/leavestype.html.twig',['findleavestype'=>$findleaves]);
             }
                  $this->addFlash('leaves',  '');
                 return $this->render('leaves/leavestype.html.twig',['findleavestype'=>$findleaves]);
        }
        else
        {
            $success="You must sign in again to access you account";
            return $this->render('college/404.html.twig',['success'=>$success]);
        }
    }
     /**
        *remove leaves type
        *
     * @Route("/remove/type/{id}", name="removeleavetype")
     */
    public function removetype($id): Response
    {
               $user1=$this->getUser();

                $em=$this->getDoctrine()->getManager();
                if($user1!=null){
                        $leaves =$this->getDoctrine()->getRepository(LeavesType::class)->findOneBy(['id' => $id]);
                if($leaves){
                        $em->remove($leaves);
                        $em->flush();
                       return $this->redirectToRoute('leavestype');
                             }
                }
                else{
                          $success="Permission revoke..please contact your administrator for more info";
                          return $this->render('college/accessdenied.html.twig',['success'=>$success]);
                   }
    }





    /**Leaves type area**/
   /**
     * @Route("/leaves/view/leavestype", name="leavestypeavailable")
     */
    public function leavesavailable(Request $request): Response
    {
     $user1=$this->getUser();
        if($user1 != null)
        {
                $em=$this->getDoctrine()->getManager();
                $connection=$em->getConnection();
                $lafesa=$connection->prepare("select * from leaves_type where lstatus='active'");;
                $lafesa->execute();
                $lafesa=$lafesa->fetchAll();
                return $this->render('leaves/viewleavestype.html.twig',['lafesa2'=>$lafesa]);
        }
        else
        {
            $success="You must sign in again to access you account";
            return $this->render('college/404.html.twig',['success'=>$success]);
        }
    }

}
