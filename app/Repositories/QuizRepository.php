<?php

namespace App\Repositories;

use App\Models\Question;
use App\Models\QuizAttempt;
use App\Models\QuizAnswer;

class QuizRepository
{
    /**
     * Get question by lesson and sequence number
     */
    public function getQuestionBySequence(int $lessonId, int $sequence): ?Question
    {
        return Question::where('lesson_id', $lessonId)
            ->with('options')
            ->skip($sequence - 1)
            ->first();
    }

    /**
     * Get total questions count for a lesson
     */
    public function getTotalQuestions(int $lessonId): int
    {
        return Question::where('lesson_id', $lessonId)->count();
    }

    /**
     * Get all questions for a lesson
     */
    public function getAllQuestions(int $lessonId)
    {
        return Question::where('lesson_id', $lessonId)
            ->with('options')
            ->get();
    }

    /**
     * Create quiz attempt
     */
    public function createAttempt(array $data): QuizAttempt
    {
        return QuizAttempt::create($data);
    }

    /**
     * Get quiz attempt by ID with relations
     */
    public function getAttemptById(int $attemptId)
    {
        return QuizAttempt::with(['lesson', 'answers.question.options'])
            ->find($attemptId);
    }

    /**
     * Get latest attempt for a lesson by user
     */
    public function getLatestAttempt(int $lessonId, int $userId): ?QuizAttempt
    {
        return QuizAttempt::where('lesson_id', $lessonId)
            ->where('user_id', $userId)
            ->latest('created_at')
            ->first();
    }

    /**
     * Get all attempts for a lesson by user
     */
    public function getUserAttempts(int $lessonId, int $userId)
    {
        return QuizAttempt::where('lesson_id', $lessonId)
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Save quiz answer
     */
    public function saveAnswer(array $data): QuizAnswer
    {
        return QuizAnswer::create($data);
    }

    /**
     * Get answers for an attempt
     */
    public function getAnswersByAttempt(int $attemptId)
    {
        return QuizAnswer::where('quiz_attempt_id', $attemptId)
            ->with(['question.options'])
            ->get();
    }

    /**
     * Check if attempt passed
     */
    public function hasPassed(int $lessonId, int $userId): bool
    {
        return QuizAttempt::where('lesson_id', $lessonId)
            ->where('user_id', $userId)
            ->where('passed', true)
            ->exists();
    }

    /**
     * Get correct option for a question
     */
    public function getCorrectOption(int $questionId)
    {
        return Question::find($questionId)
            ?->options()
            ->where('is_correct', true)
            ->first();
    }

    /**
     * Count passed attempts for a user
     */
    public function countPassedAttempts(int $userId): int
    {
        return QuizAttempt::where('user_id', $userId)
            ->where('passed', true)
            ->distinct('lesson_id')
            ->count('lesson_id');
    }
}
