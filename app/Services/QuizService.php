<?php

namespace App\Services;

use App\Repositories\QuizRepository;
use App\Repositories\LessonRepository;

class QuizService
{
    protected $quizRepository;
    protected $lessonRepository;
    protected $enrollmentService;
    protected $lessonAccessService;
    protected $certificateService;
    protected $progressService;

    public function __construct(
        QuizRepository $quizRepository,
        LessonRepository $lessonRepository,
        EnrollmentService $enrollmentService,
        LessonAccessService $lessonAccessService,
        CertificateService $certificateService,
        ProgressService $progressService
    ) {
        $this->quizRepository = $quizRepository;
        $this->lessonRepository = $lessonRepository;
        $this->enrollmentService = $enrollmentService;
        $this->lessonAccessService = $lessonAccessService;
        $this->certificateService = $certificateService;
        $this->progressService = $progressService;
    }

    /**
     * Get single quiz question by sequence
     */
    public function getQuizQuestion($lessonId, $seq = 1, $user = null): array
    {
        $lesson = $this->lessonRepository->findById($lessonId);

        if (!$lesson) {
            abort(404, 'Lesson not found.');
        }

        if ($lesson->type !== 'quiz') {
            abort(400, 'Lesson is not a quiz.');
        }

        // Check access (includes enrollment & sequential check)
        $accessCheck = $this->lessonAccessService->canAccessLesson($lesson, $user);

        if (!$accessCheck['can_access']) {
            abort(403, $accessCheck['message']);
        }

        $question = $this->quizRepository->getQuestionBySequence($lessonId, $seq);

        if (!$question) {
            abort(404, 'Question not found.');
        }

        $totalQuestions = $this->quizRepository->getTotalQuestions($lessonId);

        return [
            'question' => $question,
            'total' => $totalQuestions,
        ];
    }

    /**
     * Submit quiz answers and calculate score
     */
    public function submitQuizAnswers($lessonId, $user, $answers): array
    {
        $lesson = $this->lessonRepository->findById($lessonId);

        if (!$lesson || $lesson->type !== 'quiz') {
            abort(400, 'Invalid quiz lesson.');
        }

        // Check access (includes enrollment & sequential check)
        $accessCheck = $this->lessonAccessService->canAccessLesson($lesson, $user);

        if (!$accessCheck['can_access']) {
            abort(403, $accessCheck['message']);
        }

        $questions = $this->quizRepository->getAllQuestions($lessonId);

        $totalPoints = 0;
        $earnedPoints = 0;
        $correctCount = 0;
        $wrongCount = 0;
        $detailedAnswers = [];

        foreach ($questions as $index => $question) {
            $seq = $index + 1;
            $totalPoints += $question->points;

            $userOptionId = $answers[$seq] ?? null;
            $correctOption = $question->options->where('is_correct', true)->first();

            $isCorrect = $correctOption && $userOptionId == $correctOption->id;

            if ($isCorrect) {
                $earnedPoints += $question->points;
                $correctCount++;
            } else {
                $wrongCount++;
            }

            $detailedAnswers[] = [
                'question_id' => $question->id,
                'question_text' => $question->question_text,
                'user_answer_id' => $userOptionId,
                'correct_answer_id' => $correctOption ? $correctOption->id : null,
                'is_correct' => $isCorrect,
                'points' => $question->points,
                'earned' => $isCorrect ? $question->points : 0,
            ];
        }

        $score = $totalPoints > 0 ? round(($earnedPoints / $totalPoints) * 100, 2) : 0;
        $passed = $score >= $lesson->passing_grade;

        // Save quiz attempt with timing
        $attempt = $this->quizRepository->createAttempt([
            'lesson_id' => $lessonId,
            'user_id' => $user->id,
            'started_at' => now()->subMinutes($lesson->duration_minutes ?? 15),
            'submitted_at' => now(),
            'completed_at' => now(),
            'score' => $score,
            'passed' => $passed,
            'total_questions' => $questions->count(),
            'correct_answers' => $correctCount,
            'wrong_answers' => $wrongCount,
        ]);

        // Save individual answers
        foreach ($detailedAnswers as $ans) {
            $this->quizRepository->saveAnswer([
                'quiz_attempt_id' => $attempt->id,
                'question_id' => $ans['question_id'],
                'selected_option_id' => $ans['user_answer_id'],
                'is_correct' => $ans['is_correct'],
            ]);
        }

        // If passed, mark lesson as completed
        if ($passed) {
            $this->lessonRepository->markAsCompleted($lessonId, $user->id);

            // Check if course is now 100% complete and issue certificate
            $courseId = $lesson->section->course_id;
            $certificate = $this->checkAndIssueCertificate($user->id, $courseId);
        }

        $response = [
            'attempt_id' => $attempt->id,
            'score' => $score,
            'passed' => $passed,
            'passing_grade' => $lesson->passing_grade,
            'total_questions' => $questions->count(),
            'correct_answers' => $correctCount,
            'wrong_answers' => $wrongCount,
            'total_points' => $totalPoints,
            'earned_points' => $earnedPoints,
        ];

        if ($passed && isset($certificate)) {
            $response['certificate_issued'] = true;
            $response['certificate_number'] = $certificate->certificate_number;
            $response['message'] = 'Selamat! Quiz lulus dan Anda mendapatkan sertifikat!';
        }

        return $response;
    }

    /**
     * Get quiz attempt review with detailed answers
     */
    public function getAttemptReview($attemptId, $user): array
    {
        $attempt = $this->quizRepository->getAttemptById($attemptId);

        if (!$attempt) {
            abort(404, 'Quiz attempt not found.');
        }

        // Authorization check
        if ($attempt->user_id !== $user->id && (!isset($user->role) || $user->role !== 'admin')) {
            abort(403, 'Unauthorized to view this attempt.');
        }

        $detailedAnswers = [];
        $questions = $this->quizRepository->getAllQuestions($attempt->lesson_id);

        foreach ($questions as $index => $question) {
            $userAnswer = $attempt->answers->where('question_id', $question->id)->first();
            $correctOption = $question->options->where('is_correct', true)->first();

            $detailedAnswers[] = [
                'question_number' => $index + 1,
                'question_text' => $question->question_text,
                'explanation' => $question->explanation ?? null,
                'points' => $question->points,
                'user_answer' => $userAnswer ? [
                    'id' => $userAnswer->selected_option_id,
                    'text' => $userAnswer->selectedOption ? $userAnswer->selectedOption->option_text : 'Not answered',
                ] : null,
                'correct_answer' => $correctOption ? [
                    'id' => $correctOption->id,
                    'text' => $correctOption->option_text,
                ] : null,
                'is_correct' => $userAnswer ? $userAnswer->is_correct : false,
                'all_options' => $question->options->map(function ($opt) {
                    return [
                        'id' => $opt->id,
                        'text' => $opt->option_text,
                        'is_correct' => $opt->is_correct,
                    ];
                }),
            ];
        }

        return [
            'attempt_id' => $attempt->id,
            'lesson_id' => $attempt->lesson_id,
            'lesson_title' => $attempt->lesson->title,
            'score' => $attempt->score,
            'passed' => $attempt->passed,
            'passing_grade' => $attempt->lesson->passing_grade,
            'total_questions' => $attempt->total_questions,
            'correct_answers' => $attempt->correct_answers,
            'wrong_answers' => $attempt->wrong_answers,
            'submitted_at' => $attempt->submitted_at,
            'detailed_answers' => $detailedAnswers,
        ];
    }

    /**
     * Check course completion and issue certificate if 100%
     */
    protected function checkAndIssueCertificate(int $userId, int $courseId)
    {
        $progress = $this->progressService->calculateProgress($userId, $courseId);

        if ($progress >= 100.00) {
            return $this->certificateService->issueCertificate($userId, $courseId);
        }

        return null;
    }
}
