<?php
// Sources/app/view/quizz_solo.php
require_once __DIR__.'/../../vendor/autoload.php';

if (isset($_POST['export_pdf'])) {
    $results = $_SESSION['quiz_results'];
    
    $pdf = new \Mpdf\Mpdf();
    $pdf->SetTitle('Résultats du Quiz');
    
    $html = '<h1>Résultats du Quiz</h1>';
    foreach ($results as $index => $result) {
        $html .= "<div class='question-result'>
            <h3>Question ".($index+1)."</h3>
            <p><strong>Votre réponse:</strong> {$result['user_answer']}</p>
            <p><strong>Bonne réponse:</strong> {$result['correct_answer']}</p>
            <p><strong>Statut:</strong> ".($result['is_correct'] ? 'Correct' : 'Incorrect')."</p>
        </div>";
    }
    
    $pdf->WriteHTML($html);
    $pdf->Output('quiz_results.pdf', 'D');
    exit;
}
?>