import React from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AppLayout from '../../Layouts/AppLayout';
import { 
    BookOpen, 
    Layers, 
    Award, 
    CheckCircle2, 
    ArrowRight, 
    Video, 
    FileText, 
    HelpCircle, 
    Download, 
    Clock, 
    UserCheck, 
    ShieldCheck, 
    PlayCircle 
} from 'lucide-react';

export default function CourseShow({ course }) {
    const handleEnroll = () => {
        router.post(`/courses/${course.id}/enroll`);
    };

    const getLessonIcon = (type) => {
        switch (type) {
            case 'video':
                return <Video className="w-4 h-4 text-sky-400" />;
            case 'document':
                return <Download className="w-4 h-4 text-amber-400" />;
            case 'quiz':
                return <HelpCircle className="w-4 h-4 text-purple-400" />;
            default:
                return <FileText className="w-4 h-4 text-emerald-400" />;
        }
    };

    return (
        <AppLayout>
            <Head title={`${course.title} - Saladdin Marifi LMS`} />

            {/* Breadcrumb */}
            <nav className="flex items-center gap-2 text-xs text-slate-400 mb-6">
                <Link href="/" className="hover:text-emerald-400 transition-colors">Beranda</Link>
                <span>/</span>
                <Link href="/courses" className="hover:text-emerald-400 transition-colors">Kursus</Link>
                <span>/</span>
                <span className="text-slate-200 truncate max-w-xs">{course.title}</span>
            </nav>

            <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {/* Main Left Column (Course Details & Syllabus) */}
                <div className="lg:col-span-2 space-y-8">
                    
                    {/* Course Banner & Intro */}
                    <div className="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 sm:p-8 relative overflow-hidden shadow-xl">
                        <div className="flex flex-wrap items-center gap-2 mb-4">
                            <span className="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                {course.price === 0 ? 'Program Terbuka (Gratis)' : `Rp ${course.price.toLocaleString('id-ID')}`}
                            </span>
                            {course.is_enrolled && (
                                <span className="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500 text-slate-950 flex items-center gap-1.5 shadow">
                                    <CheckCircle2 className="w-3.5 h-3.5" /> Anda Sudah Terdaftar
                                </span>
                            )}
                        </div>

                        <h1 className="text-2xl sm:text-4xl font-extrabold text-white tracking-tight leading-tight mb-4">
                            {course.title}
                        </h1>

                        <p className="text-sm sm:text-base text-slate-300 leading-relaxed mb-6">
                            {course.description || 'Program pembelajaran komprehensif yang dirancang untuk memperluas pemahaman keilmuan Islam secara ilmiah dan aplikatif.'}
                        </p>

                        <div className="flex flex-wrap items-center gap-6 pt-6 border-t border-slate-800 text-xs text-slate-400">
                            <div className="flex items-center gap-2">
                                <div className="w-7 h-7 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold">
                                    {course.instructor_name?.charAt(0) || 'U'}
                                </div>
                                <div>
                                    <div className="text-[10px] text-slate-500">Pemateri</div>
                                    <div className="font-semibold text-white">{course.instructor_name || 'Ustadz Saladdin'}</div>
                                </div>
                            </div>

                            <div className="flex items-center gap-2">
                                <Layers className="w-4 h-4 text-emerald-400" />
                                <div>
                                    <div className="text-[10px] text-slate-500">Jumlah Modul</div>
                                    <div className="font-semibold text-white">{course.sections?.length || 0} Modul</div>
                                </div>
                            </div>

                            <div className="flex items-center gap-2">
                                <BookOpen className="w-4 h-4 text-emerald-400" />
                                <div>
                                    <div className="text-[10px] text-slate-500">Total Materi</div>
                                    <div className="font-semibold text-white">{course.total_lessons || 0} Pelajaran</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Syllabus / Modul & Lessons Breakdown */}
                    <div className="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-xl">
                        <div className="flex items-center justify-between mb-6">
                            <div>
                                <h2 className="text-xl font-bold text-white tracking-tight">
                                    Kurikulum Pembelajaran
                                </h2>
                                <p className="text-xs text-slate-400 mt-1">
                                    Materi disusun berjenjang untuk memastikan pemahaman maksimal
                                </p>
                            </div>
                            <div className="text-xs text-emerald-400 font-semibold bg-emerald-950/60 border border-emerald-500/30 px-3 py-1.5 rounded-lg">
                                {course.sections?.length || 0} Modul • {course.total_lessons || 0} Pelajaran
                            </div>
                        </div>

                        <div className="space-y-4">
                            {course.sections?.map((section, sIndex) => (
                                <div key={section.id} className="rounded-2xl bg-slate-950/60 border border-slate-800 overflow-hidden">
                                    <div className="p-4 bg-slate-950/90 border-b border-slate-800/80 flex items-center justify-between">
                                        <div className="flex items-center gap-3">
                                            <span className="w-6 h-6 rounded-md bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-xs font-bold flex items-center justify-center">
                                                {sIndex + 1}
                                            </span>
                                            <h3 className="font-bold text-sm text-slate-200">
                                                {section.title}
                                            </h3>
                                        </div>
                                        <span className="text-xs text-slate-400 font-medium">
                                            {section.lessons?.length || 0} Materi
                                        </span>
                                    </div>

                                    <div className="divide-y divide-slate-800/60">
                                        {section.lessons?.map((lesson, lIndex) => (
                                            <div
                                                key={lesson.id}
                                                className="p-3.5 px-4 flex items-center justify-between hover:bg-slate-900/40 transition-colors"
                                            >
                                                <div className="flex items-center gap-3">
                                                    <div className="p-2 rounded-lg bg-slate-900 border border-slate-800">
                                                        {getLessonIcon(lesson.type)}
                                                    </div>
                                                    <div>
                                                        <div className="text-sm font-semibold text-slate-200 flex items-center gap-2">
                                                            <span>{lesson.title}</span>
                                                            {lesson.is_completed && (
                                                                <span className="text-[10px] text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 rounded-full flex items-center gap-1">
                                                                    <CheckCircle2 className="w-2.5 h-2.5" /> Selesai
                                                                </span>
                                                            )}
                                                        </div>
                                                        <div className="text-[11px] text-slate-400 capitalize flex items-center gap-2 mt-0.5">
                                                            <span>Tipe: {lesson.type}</span>
                                                            {lesson.duration_minutes && (
                                                                <>
                                                                    <span>•</span>
                                                                    <span>{lesson.duration_minutes} Menit</span>
                                                                </>
                                                            )}
                                                        </div>
                                                    </div>
                                                </div>

                                                {/* Access link if enrolled */}
                                                {course.is_enrolled && (
                                                    <Link
                                                        href={`/learn/${course.slug}/lesson/${lesson.id}`}
                                                        className="text-xs font-semibold text-emerald-400 hover:text-emerald-300 flex items-center gap-1 px-3 py-1.5 rounded-lg bg-emerald-950/40 border border-emerald-500/20 hover:bg-emerald-950/80 transition-all shrink-0"
                                                    >
                                                        <span>Buka</span>
                                                        <PlayCircle className="w-3.5 h-3.5" />
                                                    </Link>
                                                )}
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>

                </div>

                {/* Right Column: Sticky Action Card */}
                <div className="space-y-6">
                    <div className="sticky top-28 bg-slate-900/90 border border-slate-800 rounded-3xl p-6 shadow-2xl backdrop-blur-xl">
                        
                        {/* Course Thumbnail */}
                        <div className="aspect-video w-full rounded-2xl bg-slate-950 overflow-hidden mb-6 border border-slate-800 relative">
                            {course.thumbnail ? (
                                <img
                                    src={course.thumbnail}
                                    alt={course.title}
                                    className="w-full h-full object-cover"
                                    onError={(e) => {
                                        e.target.style.display = 'none';
                                        e.target.nextSibling.style.display = 'flex';
                                    }}
                                />
                            ) : null}
                            <div
                                className="w-full h-full bg-gradient-to-tr from-slate-900 via-emerald-950 to-slate-900 flex items-center justify-center p-4 text-center"
                                style={{ display: course.thumbnail ? 'none' : 'flex' }}
                            >
                                <span className="text-sm font-bold text-emerald-300 font-arabic">
                                    {course.title}
                                </span>
                            </div>
                        </div>

                        {/* Price & Status */}
                        <div className="mb-6">
                            <div className="text-xs text-slate-400 uppercase tracking-wider font-semibold mb-1">
                                Biaya Pendaftaran
                            </div>
                            <div className="text-3xl font-extrabold text-white">
                                {course.price === 0 ? 'GRATIS' : `Rp ${course.price.toLocaleString('id-ID')}`}
                            </div>
                        </div>

                        {/* Progress Bar (if enrolled) */}
                        {course.is_enrolled && (
                            <div className="mb-6 p-4 rounded-xl bg-slate-950/60 border border-slate-800">
                                <div className="flex justify-between text-xs font-semibold text-slate-300 mb-2">
                                    <span>Kemajuan Anda</span>
                                    <span className="text-emerald-400">{course.progress}%</span>
                                </div>
                                <div className="w-full h-2 rounded-full bg-slate-800 overflow-hidden">
                                    <div 
                                        className="h-full bg-emerald-500 rounded-full transition-all duration-500"
                                        style={{ width: `${course.progress}%` }}
                                    />
                                </div>
                            </div>
                        )}

                        {/* Primary Button */}
                        {course.is_enrolled ? (
                            <Link
                                href={`/learn/${course.slug}/lesson/${course.first_lesson_id || 1}`}
                                className="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-slate-950 font-bold text-sm shadow-xl shadow-emerald-500/25 transition-all flex items-center justify-center gap-2 hover:scale-[1.01]"
                            >
                                <PlayCircle className="w-5 h-5" />
                                <span>{course.progress > 0 ? 'Lanjutkan Belajar' : 'Mulai Pembelajaran'}</span>
                            </Link>
                        ) : (
                            <button
                                onClick={handleEnroll}
                                className="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-slate-950 font-bold text-sm shadow-xl shadow-emerald-500/25 transition-all flex items-center justify-center gap-2 hover:scale-[1.01]"
                            >
                                <UserCheck className="w-5 h-5" />
                                <span>Daftar Kursus Sekarang</span>
                            </button>
                        )}

                        {/* Value Proportions */}
                        <div className="mt-6 pt-6 border-t border-slate-800/80 space-y-3 text-xs text-slate-300">
                            <div className="flex items-center gap-2.5">
                                <Award className="w-4 h-4 text-amber-400 shrink-0" />
                                <span>Sertifikat kelulusan setelah tuntas</span>
                            </div>
                            <div className="flex items-center gap-2.5">
                                <HelpCircle className="w-4 h-4 text-purple-400 shrink-0" />
                                <span>Kuis evaluasi pemahaman di tiap akhir sesi</span>
                            </div>
                            <div className="flex items-center gap-2.5">
                                <Clock className="w-4 h-4 text-sky-400 shrink-0" />
                                <span>Akses materi tanpa batas waktu</span>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </AppLayout>
    );
}
