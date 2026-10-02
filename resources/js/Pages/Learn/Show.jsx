import React, { useState, useEffect } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import confetti from 'canvas-confetti';
import { 
    ChevronLeft, 
    ChevronRight, 
    CheckCircle2, 
    PlayCircle, 
    FileText, 
    HelpCircle, 
    Download, 
    Video, 
    ArrowLeft, 
    Award, 
    Clock, 
    Menu, 
    X, 
    Check, 
    Sparkles, 
    AlertCircle 
} from 'lucide-react';

export default function LearnShow({
    course,
    syllabus = [],
    lesson,
    quiz,
    latestAttempt,
    prevLesson,
    nextLesson,
}) {
    const [sidebarOpen, setSidebarOpen] = useState(true);
    const [answers, setAnswers] = useState({});
    const [submittingQuiz, setSubmittingQuiz] = useState(false);

    // Trigger celebratory confetti if passed
    useEffect(() => {
        if (latestAttempt?.passed) {
            confetti({
                particleCount: 100,
                spread: 70,
                origin: { y: 0.6 }
            });
        }
    }, [latestAttempt]);

    const handleSelectOption = (sequence, optionId) => {
        setAnswers((prev) => ({
            ...prev,
            [sequence]: optionId,
        }));
    };

    const handleSubmitQuiz = (e) => {
        e.preventDefault();
        setSubmittingQuiz(true);
        router.post(`/learn/lessons/${lesson.id}/quiz`, { answers }, {
            preserveScroll: true,
            onFinish: () => setSubmittingQuiz(false),
        });
    };

    const handleCompleteLesson = () => {
        router.post(`/learn/lessons/${lesson.id}/complete`);
    };

    const getLessonIcon = (type, isCompleted, isCurrent) => {
        if (isCompleted) {
            return <CheckCircle2 className="w-4 h-4 text-emerald-400 shrink-0" />;
        }
        switch (type) {
            case 'video':
                return <Video className={`w-4 h-4 shrink-0 ${isCurrent ? 'text-emerald-400' : 'text-slate-400'}`} />;
            case 'document':
                return <Download className={`w-4 h-4 shrink-0 ${isCurrent ? 'text-amber-400' : 'text-slate-400'}`} />;
            case 'quiz':
                return <HelpCircle className={`w-4 h-4 shrink-0 ${isCurrent ? 'text-purple-400' : 'text-slate-400'}`} />;
            default:
                return <FileText className={`w-4 h-4 shrink-0 ${isCurrent ? 'text-emerald-400' : 'text-slate-400'}`} />;
        }
    };

    return (
        <div className="min-h-screen bg-slate-950 text-slate-100 flex flex-col antialiased selection:bg-emerald-500 selection:text-white">
            <Head title={`${lesson.title} - ${course.title}`} />

            {/* Top Learning Navigation Bar */}
            <header className="sticky top-0 z-40 h-16 bg-slate-950/95 border-b border-slate-800 backdrop-blur-md px-4 sm:px-6 flex items-center justify-between">
                <div className="flex items-center gap-3">
                    <Link
                        href="/dashboard"
                        className="p-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-300 hover:text-white hover:border-slate-700 transition-colors"
                        title="Kembali ke Dashboard"
                    >
                        <ArrowLeft className="w-4 h-4" />
                    </Link>
                    <div className="h-6 w-[1px] bg-slate-800 hidden sm:block" />
                    <div>
                        <div className="text-[10px] text-emerald-400 font-semibold uppercase tracking-wider line-clamp-1">
                            {course.title}
                        </div>
                        <h1 className="text-xs sm:text-sm font-bold text-white line-clamp-1">
                            {lesson.title}
                        </h1>
                    </div>
                </div>

                <div className="flex items-center gap-4">
                    {/* Progress Indicator */}
                    <div className="hidden md:flex items-center gap-3 text-xs text-slate-400">
                        <span>Penyelesaian:</span>
                        <div className="w-28 h-2 rounded-full bg-slate-800 overflow-hidden">
                            <div 
                                className="h-full bg-emerald-500 rounded-full transition-all duration-300"
                                style={{ width: `${course.progress}%` }}
                            />
                        </div>
                        <span className="font-semibold text-emerald-400">{course.progress}%</span>
                    </div>

                    {/* Sidebar Toggle for Mobile */}
                    <button
                        onClick={() => setSidebarOpen(!sidebarOpen)}
                        className="p-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-300 hover:text-white lg:hidden"
                    >
                        {sidebarOpen ? <X className="w-4 h-4" /> : <Menu className="w-4 h-4" />}
                    </button>
                </div>
            </header>

            {/* Learning Viewport (Content + Sidebar) */}
            <div className="flex-1 flex overflow-hidden relative">
                
                {/* Main Content Area */}
                <div className="flex-1 overflow-y-auto p-4 sm:p-8 lg:p-10 flex flex-col justify-between max-w-5xl mx-auto w-full">
                    
                    {/* Content Section */}
                    <div className="space-y-6">
                        
                        {/* 1. VIDEO LESSON */}
                        {lesson.type === 'video' && (
                            <div className="space-y-6">
                                <div className="aspect-video w-full rounded-2xl bg-black overflow-hidden shadow-2xl border border-slate-800 relative">
                                    {lesson.content_url ? (
                                        <iframe
                                            src={lesson.content_url}
                                            title={lesson.title}
                                            className="w-full h-full border-0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowFullScreen
                                        />
                                    ) : (
                                        <div className="w-full h-full flex flex-col items-center justify-center text-slate-500">
                                            <Video className="w-12 h-12 mb-2" />
                                            <span>Video belum diunggah.</span>
                                        </div>
                                    )}
                                </div>

                                {lesson.content_text && (
                                    <div className="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-6">
                                        <h3 className="text-xs uppercase font-bold text-slate-400 tracking-wider mb-3">
                                            Catatan Pelajaran
                                        </h3>
                                        <div 
                                            className="prose prose-invert prose-emerald max-w-none text-slate-300 text-sm leading-relaxed"
                                            dangerouslySetInnerHTML={{ __html: lesson.content_text }}
                                        />
                                    </div>
                                )}
                            </div>
                        )}

                        {/* 2. TEXT / ARTICLE LESSON */}
                        {lesson.type === 'text' && (
                            <div className="bg-slate-900/80 border border-slate-800 rounded-3xl p-6 sm:p-10 shadow-2xl space-y-6">
                                <div className="border-b border-slate-800 pb-4">
                                    <span className="text-xs uppercase font-bold text-emerald-400 tracking-wider">
                                        Materi Bacaan & Telaah
                                    </span>
                                    <h2 className="text-2xl sm:text-3xl font-bold text-white mt-1">
                                        {lesson.title}
                                    </h2>
                                </div>
                                <div
                                    className="prose prose-invert prose-emerald max-w-none text-slate-200 text-sm sm:text-base leading-relaxed space-y-4"
                                    dangerouslySetInnerHTML={{ __html: lesson.content_text || '<p>Materi bacaan belum tersedia.</p>' }}
                                />
                            </div>
                        )}

                        {/* 3. DOCUMENT LESSON */}
                        {lesson.type === 'document' && (
                            <div className="bg-slate-900/80 border border-slate-800 rounded-3xl p-8 sm:p-12 text-center shadow-2xl space-y-6">
                                <div className="w-16 h-16 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center mx-auto shadow-lg">
                                    <Download className="w-8 h-8" />
                                </div>
                                <div>
                                    <h2 className="text-2xl font-bold text-white mb-2">{lesson.title}</h2>
                                    <p className="text-sm text-slate-400 max-w-md mx-auto">
                                        Unduh dokumen pendukung untuk mempelajari materi ini secara luring atau mencetaknya sebagai pegangan.
                                    </p>
                                </div>

                                {lesson.content_text && (
                                    <div 
                                        className="text-xs text-slate-400 max-w-md mx-auto text-left bg-slate-950/60 p-4 rounded-xl border border-slate-800"
                                        dangerouslySetInnerHTML={{ __html: lesson.content_text }}
                                    />
                                )}

                                <div>
                                    {lesson.content_path ? (
                                        <a
                                            href={lesson.content_path}
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            className="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs shadow-lg shadow-amber-500/20 transition-all hover:scale-105"
                                        >
                                            <Download className="w-4 h-4" />
                                            Unduh Dokumen Modul (PDF)
                                        </a>
                                    ) : (
                                        <div className="text-xs text-slate-500 italic">File dokumen belum dilampirkan.</div>
                                    )}
                                </div>
                            </div>
                        )}

                        {/* 4. INTERACTIVE QUIZ LESSON */}
                        {lesson.type === 'quiz' && (
                            <div className="space-y-6">
                                
                                {/* Quiz Header Card */}
                                <div className="bg-slate-900/90 border border-purple-500/30 rounded-3xl p-6 sm:p-8 shadow-2xl relative overflow-hidden">
                                    <div className="absolute top-0 right-0 w-64 h-64 bg-purple-500/10 rounded-full blur-3xl pointer-events-none" />
                                    
                                    <div className="flex flex-wrap items-center justify-between gap-4 mb-4">
                                        <div className="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-purple-500/20 text-purple-400 text-xs font-semibold">
                                            <HelpCircle className="w-3.5 h-3.5" />
                                            Ujian Pemahaman Materi
                                        </div>
                                        <div className="flex items-center gap-3 text-xs text-slate-400">
                                            <span>KKM Kelulusan: <strong className="text-white">{quiz?.passing_grade}%</strong></span>
                                            <span>•</span>
                                            <span>Durasi: <strong className="text-white">{quiz?.duration_minutes} Menit</strong></span>
                                        </div>
                                    </div>

                                    <h2 className="text-2xl sm:text-3xl font-extrabold text-white mb-2">
                                        {lesson.title}
                                    </h2>
                                    {lesson.content_text && (
                                        <div 
                                            className="text-xs text-slate-400"
                                            dangerouslySetInnerHTML={{ __html: lesson.content_text }}
                                        />
                                    )}

                                    {/* Attempt Result Banner if already attempted */}
                                    {latestAttempt && (
                                        <div className={`mt-6 p-4 rounded-xl border flex items-center justify-between gap-4 ${
                                            latestAttempt.passed
                                                ? 'bg-emerald-950/60 border-emerald-500/40 text-emerald-200'
                                                : 'bg-rose-950/60 border-rose-500/40 text-rose-200'
                                        }`}>
                                            <div className="flex items-center gap-3">
                                                {latestAttempt.passed ? (
                                                    <CheckCircle2 className="w-6 h-6 text-emerald-400 shrink-0" />
                                                ) : (
                                                    <AlertCircle className="w-6 h-6 text-rose-400 shrink-0" />
                                                )}
                                                <div>
                                                    <div className="font-bold text-sm">
                                                        {latestAttempt.passed ? 'LULUS UJIAN KUIS' : 'BELUM MENCAPAI KKM'}
                                                    </div>
                                                    <div className="text-xs opacity-90">
                                                        Nilai Anda: <strong className="text-white font-extrabold">{latestAttempt.score}</strong> (Benar {latestAttempt.correct_answers} dari {latestAttempt.total_questions} soal)
                                                    </div>
                                                </div>
                                            </div>
                                            {latestAttempt.passed && (
                                                <div className="hidden sm:flex items-center gap-1.5 text-xs font-bold text-amber-400 bg-amber-500/10 border border-amber-500/20 px-3 py-1.5 rounded-lg">
                                                    <Award className="w-4 h-4" /> Kuis Selesai
                                                </div>
                                            )}
                                        </div>
                                    )}
                                </div>

                                {/* Questions Form */}
                                {quiz?.questions?.length > 0 ? (
                                    <form onSubmit={handleSubmitQuiz} className="space-y-6">
                                        {quiz.questions.map((question, qIdx) => (
                                            <div
                                                key={question.id}
                                                className="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 shadow-xl space-y-4"
                                            >
                                                <div className="flex items-start gap-3">
                                                    <span className="w-7 h-7 rounded-lg bg-emerald-500/20 text-emerald-400 text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">
                                                        {qIdx + 1}
                                                    </span>
                                                    <div className="text-sm sm:text-base font-semibold text-slate-100 leading-relaxed">
                                                        {question.question_text}
                                                    </div>
                                                </div>

                                                {/* Options list */}
                                                <div className="space-y-2.5 pt-2">
                                                    {question.options?.map((option) => {
                                                        const isSelected = answers[question.sequence] === option.id;
                                                        return (
                                                            <label
                                                                key={option.id}
                                                                onClick={() => handleSelectOption(question.sequence, option.id)}
                                                                className={`flex items-center gap-3 p-3.5 rounded-xl border text-xs sm:text-sm font-medium cursor-pointer transition-all ${
                                                                    isSelected
                                                                        ? 'bg-emerald-950/60 border-emerald-500 text-white shadow-md shadow-emerald-500/10'
                                                                        : 'bg-slate-950/60 border-slate-800 text-slate-300 hover:bg-slate-900 hover:border-slate-700'
                                                                }`}
                                                            >
                                                                <div className={`w-4 h-4 rounded-full border flex items-center justify-center shrink-0 transition-colors ${
                                                                    isSelected 
                                                                        ? 'border-emerald-500 bg-emerald-500 text-slate-950' 
                                                                        : 'border-slate-600'
                                                                }`}>
                                                                    {isSelected && <div className="w-1.5 h-1.5 rounded-full bg-slate-950" />}
                                                                </div>
                                                                <span>{option.option_text}</span>
                                                            </label>
                                                        );
                                                    })}
                                                </div>
                                            </div>
                                        ))}

                                        <div className="pt-4 flex justify-end">
                                            <button
                                                type="submit"
                                                disabled={submittingQuiz || Object.keys(answers).length === 0}
                                                className="px-8 py-3.5 rounded-xl bg-gradient-to-r from-purple-500 to-indigo-500 hover:from-purple-400 hover:to-indigo-400 text-white font-bold text-sm shadow-xl shadow-purple-500/25 transition-all flex items-center gap-2 hover:scale-[1.02] disabled:opacity-50"
                                            >
                                                <Check className="w-4 h-4" />
                                                <span>{submittingQuiz ? 'Memeriksa Jawaban...' : 'Kirim Jawaban Kuis'}</span>
                                            </button>
                                        </div>
                                    </form>
                                ) : (
                                    <div className="p-8 text-center text-slate-500 bg-slate-900/40 rounded-2xl border border-slate-800">
                                        Belum ada soal pada kuis ini.
                                    </div>
                                )}
                            </div>
                        )}

                    </div>

                    {/* Bottom Action / Navigation Bar */}
                    <div className="mt-12 pt-6 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
                        {prevLesson ? (
                            <Link
                                href={`/learn/${course.slug}/lesson/${prevLesson.id}`}
                                className="w-full sm:w-auto px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-800 hover:border-slate-700 text-slate-300 font-semibold text-xs flex items-center justify-center gap-2 transition-colors"
                            >
                                <ChevronLeft className="w-4 h-4" />
                                <span>Materi Sebelumnya</span>
                            </Link>
                        ) : <div className="hidden sm:block" />}

                        <div className="flex items-center gap-3 w-full sm:w-auto justify-end">
                            {/* Complete lesson button for non-quiz */}
                            {lesson.type !== 'quiz' && !lesson.is_completed && (
                                <button
                                    onClick={handleCompleteLesson}
                                    className="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-2 transition-all hover:scale-[1.02]"
                                >
                                    <Check className="w-4 h-4" />
                                    <span>Tandai Selesai & Lanjut</span>
                                </button>
                            )}

                            {nextLesson && (lesson.is_completed || lesson.type === 'quiz') && (
                                <Link
                                    href={`/learn/${course.slug}/lesson/${nextLesson.id}`}
                                    className="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-semibold text-xs flex items-center justify-center gap-2 transition-colors"
                                >
                                    <span>Lanjut ke Berikutnya</span>
                                    <ChevronRight className="w-4 h-4" />
                                </Link>
                            )}
                        </div>
                    </div>

                </div>

                {/* Sidebar (Syllabus & Modules) */}
                <aside className={`
                    w-80 shrink-0 border-l border-slate-800 bg-slate-950/95 backdrop-blur-xl flex flex-col fixed inset-y-16 right-0 z-30 transition-transform duration-300 lg:static lg:translate-x-0
                    ${sidebarOpen ? 'translate-x-0' : 'translate-x-full lg:translate-x-0'}
                `}>
                    <div className="p-4 border-b border-slate-800 flex items-center justify-between">
                        <div className="text-xs font-bold text-white uppercase tracking-wider">
                            Daftar Modul Kursus
                        </div>
                        <span className="text-[11px] text-slate-400">
                            {course.completed_count} / {course.total_lessons} Selesai
                        </span>
                    </div>

                    <div className="flex-1 overflow-y-auto p-3 space-y-4">
                        {syllabus.map((section, sIdx) => (
                            <div key={section.id} className="space-y-1.5">
                                <div className="px-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                                    {section.title}
                                </div>
                                <div className="space-y-1">
                                    {section.lessons?.map((les) => (
                                        <Link
                                            key={les.id}
                                            href={`/learn/${course.slug}/lesson/${les.id}`}
                                            className={`w-full p-2.5 rounded-xl flex items-center gap-3 text-left transition-all text-xs font-medium ${
                                                les.is_current
                                                    ? 'bg-emerald-500/10 text-emerald-300 border border-emerald-500/30'
                                                    : 'text-slate-300 hover:bg-slate-900 border border-transparent'
                                            }`}
                                        >
                                            {getLessonIcon(les.type, les.is_completed, les.is_current)}
                                            <span className="line-clamp-1 flex-1">{les.title}</span>
                                        </Link>
                                    ))}
                                </div>
                            </div>
                        ))}
                    </div>

                    {/* Progress Banner at bottom of sidebar */}
                    <div className="p-4 border-t border-slate-800 bg-slate-900/50">
                        <div className="flex items-center justify-between text-xs text-slate-300 mb-2 font-semibold">
                            <span>Sertifikat Kelulusan</span>
                            <span className="text-amber-400 font-bold">{course.progress}%</span>
                        </div>
                        <div className="w-full h-2 rounded-full bg-slate-800 overflow-hidden">
                            <div 
                                className="h-full bg-gradient-to-r from-emerald-500 to-amber-400 rounded-full"
                                style={{ width: `${course.progress}%` }}
                            />
                        </div>
                        <p className="text-[10px] text-slate-500 mt-2">
                            {course.progress >= 100 
                                ? 'Sertifikat Anda telah aktif dan dapat diunduh.' 
                                : 'Selesaikan 100% materi untuk membuka sertifikat.'}
                        </p>
                    </div>
                </aside>

            </div>
        </div>
    );
}
