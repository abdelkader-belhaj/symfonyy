<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\ActivityRepository;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\User\LoginFormType;

use App\Entity\Commentaire;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use App\Entity\Activity; 
use Doctrine\ORM\EntityManagerInterface;


final class BackendActivityController extends AbstractController
{
    #[Route('/backend/activity', name: 'app_backend_activity')]
    public function index(ActivityRepository $activityRepository): Response
    {
        // Récupérer toutes les activités pour un affichage complémentaire
        $activities = $activityRepository->findAll();
        $activityCount = count($activities); // Calcul du nombre d'activités

        // Récupérer les données agrégées : pour chaque date et pour chaque type, le nombre d'activités
        $aggregatedData = $activityRepository->getActivityCountByTypeAndDateLast10Days();

        $dates = [];
        $seriesData = [];

        // On parcourt les données agrégées
        foreach ($aggregatedData as $row) {
            $date = $row['date'];  // format "YYYY-MM-DD"
            $type = $row['type'] ?? 'Non défini';
            
            // On collecte toutes les dates uniques (axe horizontal)
            $dates[$date] = $date;
            
            // On construit un tableau associatif : pour chaque type, pour chaque date, on enregistre le nombre
            $seriesData[$type][$date] = (int)$row['count'];
        }

        // Transformer le tableau des dates en tableau indexé et le trier
        $dates = array_values($dates);
        sort($dates);

        // Construction du tableau final des séries pour ApexCharts :
        // Pour chaque type, pour chaque date, si aucune donnée n'est présente, on place 0.
        $chartSeries = [];
        foreach ($seriesData as $type => $dataByDate) {
            $data = [];
            foreach ($dates as $date) {
                $data[] = isset($dataByDate[$date]) ? $dataByDate[$date] : 0;
            }
            $chartSeries[] = [
                'name' => $type,
                'data' => $data,
            ];
        }

        return $this->render('backend_activity/index.html.twig', [
            'activities'   => $activities,
            'activityCount'=> $activityCount,
            'chartDates'   => $dates,
            'chartSeries'  => $chartSeries,
        ]);
    }

  




    #[Route('/backend/activity/events', name: 'app_backend_activity_events')]
    public function getActivityEvents(ActivityRepository $activityRepository): Response
    {
        $activities = $activityRepository->findAll();
        
        $events = [];
    
        foreach ($activities as $activity) {
            $events[] = [
                'title' => $activity->getTitre(),
                'start' => $activity->getDate()->format('Y-m-d H:i:s'),
                'color' => $activity->isApproved() ? 'green' : 'red', // Couleur en fonction de l'approbation

            ];
          

        }
    
        return $this->json($events);
    }
    





    #[Route('/backend/activity/approve/{id}', name: 'app_backend_activity_approve')]
public function approve(Activity $activity, EntityManagerInterface $entityManager): Response
{
    $activity->setApproved(true);
    $entityManager->flush();

    $this->addFlash('success', 'L\'activité a été approuvée avec succès.');

    return $this->redirectToRoute('app_backend_activity');
}


#[Route('/backend/dashboard', name: 'backend_dashboard')]
public function adminDashboard(): Response
{
    return $this->render('backend_activity/dashboard.html.twig');
}







#[Route('/backend/showDetails/{id}', name: 'backend_showDetails', methods: ['GET', 'POST'])]
public function showDetails(ActivityRepository $activityRepository, int $id, CommentaireRepository $commentaireRepository,Request $request): Response
{
    $activity = $activityRepository->find($id);
    $averageNotes = $commentaireRepository->findAverageNoteByActivity();
    // Création du formulaire de notation
    $form = $this->createForm(CommentaireType::class); // Remplacez "NoteType" par votre type de formulaire réel
    $form->handleRequest($request); // Gestion des données soumise


    if (!$activity) {
        throw $this->createNotFoundException('Aucune activité trouvée pour cet ID.');
    }

    // Décoder le JSON stocké dans imageFileName pour obtenir un tableau d'images
    $images = [];
    if ($activity->getImageFileName()) {
        $images = json_decode($activity->getImageFileName(), true);
    }

    return $this->render('backend_activity/showDetails.html.twig', [
        'activity' => $activity,
        'images'   => $images,
        'averageNotes' => $averageNotes, 
    ]);
}


}
