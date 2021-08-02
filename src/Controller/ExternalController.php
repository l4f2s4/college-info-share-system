<?php

namespace App\Controller;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\Promotion;
use App\Entity\Department;
use App\Entity\User;
use App\Entity\Student;
use App\Entity\Themsetting;
use App\Entity\Project;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Session\Session;

class ExternalController extends AbstractController
{
   /**
     *  @Route("/reset/password/email", name="reset")
     */

    public function forgottenPassword(
        Request $request,
        UserPasswordEncoderInterface $encoder,
        \Swift_Mailer $mailer,
        TokenGeneratorInterface $tokenGenerator
    ): Response
    {

        if ($request->isMethod('POST')) {

            $email = $request->request->get('kind');
             if(!empty($email)){
            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
            /* @var $user User */

            if ($user === null) {
                $this->addFlash('verify', 'Email not found!');
                return $this->render('college/forgotpassword.html.twig');
            }
            $token = $tokenGenerator->generateToken();

            try{
                $user->setResetToken($token);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('verify','Connection problem');
                return $this->render('college/forgotpassword.html.twig');
            }

            $url = $this->generateUrl('app_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

            $message = (new \Swift_Message('Password Reset Link For COAF'))
                ->setFrom('universitytaken920@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    "Please Click On This link : " . $url." to reset your password.",
                    'text/html'
                );

            $mailer->send($message);

            return $this->redirectToRoute('app_login');
             }
             else{
                $this->addFlash('verify', 'This field cannot be empty');
                return $this->render('college/forgotpassword.html.twig');
             }
        }

        return $this->render('college/forgotpassword.html.twig');
     }


    /**
     * @Route("/reset_password/{token}", name="app_reset_password")
     */
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
    {

 if ($request->isMethod('POST'))
     {
           $newpass=$request->request->get('newpass');
           $conf=$request->request->get('confirm');
           if(!empty($newpass) || !empty($conf))
           {
                if(strlen($newpass)>7)
                {
                     if($newpass==$conf)
                       {
                            $entityManager = $this->getDoctrine()->getManager();

                            $user = $entityManager->getRepository(User::class)->findOneBy(['resetToken' => $token]);
                            /* @var $user User */

                            if ($user === null) {
                                $this->addFlash('dangerToken', 'Token Incorrect');
                                return $this->render('college/passwordreset.html.twig', ['token' => $token]);
                            }

                            $user->setResetToken(null);
                            $user->setPassword($passwordEncoder->encodePassword($user, $conf));
                            $entityManager->flush();


                            return $this->redirectToRoute('app_login');
                     }
                    else
                    {
                            $this->addFlash('dangerToken',  'Password mismatch!');
                            return $this->render('college/passwordreset.html', ['token' => $token]);
                    }
                }
             else
                {
                    $this->addFlash('dangerToken', 'Password must be at least 8 characters!');
                    return $this->render('college/passwordreset.html.twig', ['token' => $token]);
               }
          }
         else
         {
                $this->addFlash('dangerToken',  'All fields are required!');
                return $this->render('college/passwordreset.html.twig', ['token' => $token]);
         }
    }
   else
    {

        return $this->render('college/passwordreset.html.twig', ['token' => $token]);
    }

}


 /**
     * @Route("/project/{id}/close/means/end", name="close_project")
     */

public function closeproject(Request $request,$id)
{
         $user1=$this->getUser();
   if($user1 != null )
        {
           $project=new Project();
           $project5 =$this->getDoctrine()->getRepository(Project::class)->findOneBy(['id' => $id]);
            if($project5){
            $project5->setStatus('inactive');
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirect($this->generateUrl('project'));
            }

   }
   else
   {
            $success="You must sign in again to access you account";
            return $this->render('college/404.html.twig',['success'=>$success]);
   }
}


 /**
     * @Route("/project/{id}/buytime", name="buytime_project")
     */

public function buytimeproject(Request $request,$id)
{
         $user1=$this->getUser();
   if($user1 != null )
        {
           $project=new Project();
           $project5 =$this->getDoctrine()->getRepository(Project::class)->findOneBy(['id' => $id]);
            $today=new \DateTime('now');
            $today=$today->format('Y-m-d');
            $week=new \DateTime('+2 week');
            $week=$week->format('Y-m-d');
            if($project5){
            $deadline=$project5->getDeadline();
            if($deadline !=$today){
            $project5->setStatus('active');
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirect($this->generateUrl('project'));

            }
            else{
            $project5->setDeadline($week);
            $project5->setStatus('active');
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirect($this->generateUrl('project'));
            }

            }
   }
   else
   {
            $success="You must sign in again to access you account";
            return $this->render('college/404.html.twig',['success'=>$success]);
   }
}
 /**
    *@Route("/themes/setting/update", name="themessetting")
    *
    */
   public function themessetting(Request $request):Response
    {
     $id=1;
      $themes =$this->getDoctrine()->getRepository(Themsetting::class)->findOneBy(['id'=>$id]);
        $form = $this->createFormBuilder($themes)

        ->add('websitename',TextType::class, array('attr' => array('class' => 'form-control','readonly'=>'true')))
        ->add('lightlogo',FileType::class, array('mapped'=>false,'data_class'=>Themsetting::class,'required'=>false,'attr' => array('class' => 'form-control')))
        ->add('favicon',FileType::class, array('mapped'=>false,'data_class'=>Themsetting::class,'required'=>false,'attr' => array('class' => 'form-control')))
        ->add('save',SubmitType::class, array(
            'label' => 'Save',
            'attr' => array('class' => 'btn btn-primary submit-btn')
        ))
        ->getForm();

        $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
                          // we must transform the image string from Db  to File to respect the form types
              $oldFileName = $themes->getLightlogo();
              $oldfav = $themes->getFavicon();
                   /** @var UploadedFile $uploadedFile */
                   /** @var UploadedFile $uploadedFavicon */

            $uploadedFile = $form['lightlogo']->getData();
            $uploadedFavicon = $form['favicon']->getData();
            if ($uploadedFile) {
                $destination = $this->getParameter('themes_directory');

                $newFilename = md5(uniqid()).'.'.$uploadedFile->guessExtension();
                  try {
                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                }catch(FileException $e){
                }
                $themes->setLightlogo($newFilename);
                $themes->setFavicon($oldfav);

            }
          else if ($uploadedFavicon) {
                $destination12 = $this->getParameter('themes_directory');

                $newFilename12 = md5(uniqid()).'.'.$uploadedFavicon->guessExtension();
                  try {
                $uploadedFavicon->move(
                    $destination12,
                    $newFilename12
                );
                }catch(FileException $e){
                }
                $themes->setFavicon($newFilename12);
                $themes->setLightlogo($oldFileName);
          }
            else{
               $themes->setLightlogo($oldFileName);
                $themes->setFavicon($oldfav);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirect($this->generateUrl('themessetting'));
        }
        return $this->render('Settings/themsetting.html.twig',array('form'=>$form->createView(),'themes'=>$themes

                ));
    }

}
