import React from 'react';
import { Head, Link, usePage } from '@inertiajs/react';
import AppLayout from '../../Layouts/AppLayout';
import { 
    BookOpen, 
    Award, 
    CheckCircle2, 
    Clock, 
    PlayCircle, 
    ArrowRight, 
    Sparkles, 
    Shield, 
    Layers, 
    Users, 
    GraduationCap 
} from 'lucide-react';

export default function DashboardIndex({ stats, adminStats, continueLearning, enrolledCourses = [] }) {
    const { auth } = usePage().props;
    const user = auth?.user;

    return (
        <AppLayout>
            <Head title="Dashboard Belajar - Saladdin Marifi LMS" />

            {/* Header / Welcome Banner */}
            <div className="relative rounded-3xl bg-gradient-to-r from-emerald-950 via-slate-900 to-slate-900 border border-emerald-500/30 p-6 sm:p-10 mb-8 overflow-hidden shadow-2xl">
                <div className="absolute top-0 right-0 w-80 h-80 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none" />
                
                <div className="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div className="space-y-2 max-w-2xl">
                        <div className="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-400 text-xs font-semibold">
                            <Sparkles className="w-3.5 h-3.5" />
                            Ruang Belajar Mandiri
                        </div>
                        <h1 className="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                            Ahlan wa Sahlan, <span className="text-emerald-400">{user?.name}</span>!
                        </h1>
                        <p className="text-xs sm:text-sm text-slate-300 leading-relaxed">
                            Teruslah istiqamah dalam menuntut ilmu. Pantau perkembangan belajar dan selesaikan seluruh modul untuk meraih sertifikat resmi.
                        </p>
                    </div>

                    <div className="flex flex-wrap items-center gap-3">
                        <Link
                            href="/courses"
                            className="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-semibold text-xs transition-colors flex items-center gap-2 border border-slate-700"
                        >
                            <BookOpen className="w-4 h-4 text-emerald-400" />
                            Eksplor Kursus Baru
                        </Link>
                        <Link
                            href="/my-certificates"
                            className="px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs shadow-lg shadow-amber-500/20 transition-all flex items-center gap-1.5 hover:scale-[1.02]"
                        >
                            <Award className="w-4 h-4" />
                            Sertifikat Saya ({stats?.certificates || 0})
                        </Link>
                    </div>
                </div>
            </div>

            {/* Admin Overview Metrics (if role === admin) */}
            {adminStats && (
                <div className="mb-8 p-5 rounded-2xl bg-amber-950/30 border border-amber-500/30">
                    <div className="flex items-center gap-2 text-xs font-bold text-amber-400 uppercase tracking-wider mb-4">
                        <Shield className="w-4 h-4" />
                        Ringkasan Platform (Administrator)
                    </div>
                    <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div className="p-4 rounded-xl bg-slate-900/80 border border-slate-800">
                            <div className="text-xs text-slate-400">Total Program Kursus</div>
                            <div className="text-2xl font-bold text-white mt-1">{adminStats.total_courses} Kursus</div>
                        </div>
                        <div className="p-4 rounded-xl bg-slate-900/80 border border-slate-800">
                            <div className="text-xs text-slate-400">Total Siswa Terdaftar</div>
                            <div className="text-2xl font-bold text-emerald-400 mt-1">{adminStats.total_students} Akun</div>
                        </div>
                        <div className="p-4 rounded-xl bg-slate-900/80 border border-slate-800">
                            <div className="text-xs text-slate-400">Total Enrollment Pembelajaran</div>
                            <div className="text-2xl font-bold text-teal-400 mt-1">{adminStats.total_enrollments} Pendaftaran</div>
                        </div>
                    </div>
                </div>
            )}

            {/* Student Stats Cards */}
            <div className="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div className="p-5 rounded-2xl bg-slate-900/80 border border-slate-800 flex items-center gap-4">
                    <div className="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0">
                        <BookOpen className="w-6 h-6" />
                    </div>
                    <div>
                        <div className="text-xs text-slate-400 font-medium">Kursus Terdaftar</div>
                        <div className="text-2xl font-extrabold text-white mt-0.5">{stats?.total_enrolled || 0}</div>
                    </div>
                </div>

                <div className="p-5 rounded-2xl bg-slate-900/80 border border-slate-800 flex items-center gap-4">
                    <div className="w-12 h-12 rounded-xl bg-sky-500/10 border border-sky-500/20 text-sky-400 flex items-center justify-center shrink-0">
                        <Clock className="w-6 h-6" />
                    </div>
                    <div>
                        <div className="text-xs text-slate-400 font-medium">Sedang Berjalan</div>
                        <div className="text-2xl font-extrabold text-sky-400 mt-0.5">{stats?.in_progress || 0}</div>
                    </div>
                </div>

                <div className="p-5 rounded-2xl bg-slate-900/80 border border-slate-800 flex items-center gap-4">
                    <div className="w-12 h-12 rounded-xl bg-teal-500/10 border border-teal-500/20 text-teal-400 flex items-center justify-center shrink-0">
                        <CheckCircle2 className="w-6 h-6" />
                    </div>
                    <div>
                        <div className="text-xs text-slate-400 font-medium">Kursus Selesai</div>
                        <div className="text-2xl font-extrabold text-teal-400 mt-0.5">{stats?.completed || 0}</div>
                    </div>
                </div>

                <div className="p-5 rounded-2xl bg-slate-900/80 border border-slate-800 flex items-center gap-4">
                    <div className="w-12 h-12 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center shrink-0">
                        <Award className="w-6 h-6" />
                    </div>
                    <div>
                        <div className="text-xs text-slate-400 font-medium">Sertifikat Diraih</div>
                        <div className="text-2xl font-extrabold text-amber-400 mt-0.5">{stats?.certificates || 0}</div>
                    </div>
                </div>
            </div>

            {/* Resume Learning Spotlight Card (if any) */}
            {continueLearning && (
                <div className="mb-10 rounded-2xl bg-slate-900/90 border border-emerald-500/40 p-6 flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-xl relative overflow-hidden">
                    <div className="flex items-center gap-4">
                        <div className="w-12 h-12 rounded-2xl bg-emerald-500 flex items-center justify-center text-slate-950 font-bold shrink-0 shadow-lg shadow-emerald-500/30">
                            <PlayCircle className="w-7 h-7" />
                        </div>
                        <div>
                            <span className="text-[10px] uppercase font-bold tracking-wider text-emerald-400">
                                Lanjutkan Pembelajaran Terakhir
                            </span>
                            <h3 className="text-base sm:text-lg font-bold text-white mt-0.5">
                                {continueLearning.next_lesson?.title || continueLearning.last_lesson?.title}
                            </h3>
                            <p className="text-xs text-slate-400 mt-0.5">
                                Dari kursus: <span className="text-slate-200 font-medium">{continueLearning.course.title}</span> • {continueLearning.section?.title}
                            </p>
                        </div>
                    </div>

                    <Link
                        href={`/learn/${continueLearning.course.slug}/lesson/${continueLearning.next_lesson?.id || continueLearning.last_lesson?.id}`}
                        className="px-6 py-3 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs transition-all shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-2 hover:scale-[1.02] shrink-0"
                    >
                        <span>Lanjutkan Sekarang</span>
                        <ArrowRight className="w-4 h-4" />
                    </Link>
                </div>
            )}

            {/* My Enrolled Courses Section */}
            <div>
                <div className="flex items-center justify-between mb-6">
                    <div>
                        <h2 className="text-xl font-bold text-white tracking-tight">
                            Kursus yang Sedang Diikuti
                        </h2>
                        <p className="text-xs text-slate-400 mt-1">
                            Daftar seluruh materi yang telah Anda daftarkan
                        </p>
                    </div>
                </div>

                {enrolledCourses.length === 0 ? (
                    <div className="text-center py-16 px-4 bg-slate-900/50 rounded-2xl border border-slate-800">
                        <BookOpen className="w-12 h-12 text-slate-600 mx-auto mb-3" />
                        <h3 className="text-base font-semibold text-slate-300">Belum ada kursus yang diikuti</h3>
                        <p className="text-xs text-slate-500 mt-1 max-w-sm mx-auto mb-4">
                            Mulai langkah menuntut ilmu dengan memilih dari ragam katalog kursus kami.
                        </p>
                        <Link
                            href="/courses"
                            className="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs shadow-lg shadow-emerald-500/20 transition-all"
                        >
                            Jelajahi Katalog Kursus
                        </Link>
                    </div>
                ) : (
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {enrolledCourses.map((course) => (
                            <div
                                key={course.id}
                                className="group flex flex-col rounded-2xl bg-slate-900/80 border border-slate-800 hover:border-emerald-500/40 transition-all duration-300 hover:shadow-xl hover:shadow-emerald-500/5 overflow-hidden"
                            >
                                <div className="relative aspect-video w-full bg-slate-950 overflow-hidden">
                                    {course.thumbnail ? (
                                        <img
                                            src={course.thumbnail}
                                            alt={course.title}
                                            className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
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

                                    <div className="absolute top-3 right-3">
                                        <span className="px-2.5 py-1 rounded-md text-[11px] font-bold bg-slate-950/80 backdrop-blur-md text-emerald-400 border border-emerald-500/40">
                                            {course.progress}%
                                        </span>
                                    </div>
                                </div>

                                <div className="p-5 flex-1 flex flex-col justify-between">
                                    <div>
                                        <div className="text-[11px] text-slate-400 mb-1">
                                            Oleh: {course.instructor_name || 'Ustadz Saladdin'}
                                        </div>
                                        <h3 className="text-base font-bold text-white group-hover:text-emerald-300 transition-colors line-clamp-2 mb-3">
                                            {course.title}
                                        </h3>
                                    </div>

                                    <div className="pt-4 border-t border-slate-800">
                                        <div className="flex justify-between text-[11px] text-slate-400 mb-1.5">
                                            <span>Penyelesaian</span>
                                            <span>{course.completed_lessons} / {course.total_lessons} Materi</span>
                                        </div>
                                        <div className="w-full h-2 rounded-full bg-slate-800 overflow-hidden mb-4">
                                            <div 
                                                className="h-full bg-emerald-500 rounded-full transition-all duration-500"
                                                style={{ width: `${course.progress}%` }}
                                            />
                                        </div>

                                        <Link
                                            href={`/learn/${course.slug}/lesson/${course.first_lesson_id || 1}`}
                                            className="w-full py-2.5 px-4 rounded-xl text-xs font-bold text-center flex items-center justify-center gap-2 transition-all bg-emerald-500 hover:bg-emerald-400 text-slate-950 shadow-md shadow-emerald-500/10"
                                        >
                                            <PlayCircle className="w-4 h-4" />
                                            <span>{course.progress >= 100 ? 'Pelajari Ulang' : 'Lanjut Belajar'}</span>
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
