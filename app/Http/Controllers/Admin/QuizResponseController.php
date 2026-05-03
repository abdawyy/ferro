<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuizSession;
use App\Support\SkinQuizCatalog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuizResponseController extends Controller
{
    public function index(Request $request): View
    {
        $query = QuizSession::query()
            ->with('lead')
            ->whereNotNull('lead_id')
            ->latest();

        if ($search = $request->input('search')) {
            $query->whereHas('lead', function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%");
            });
        }

        $sessions = $query->paginate(25)->withQueryString();

        return view('admin.quiz-responses.index', compact('sessions'));
    }

    public function show(QuizSession $quiz_session): View
    {
        $quiz_session->load('lead');
        $questions = SkinQuizCatalog::questions();

        return view('admin.quiz-responses.show', [
            'session'   => $quiz_session,
            'questions' => $questions,
        ]);
    }
}
