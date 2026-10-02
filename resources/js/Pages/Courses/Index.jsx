import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AppLayout from '../../Layouts/AppLayout';
import { 
    Search, 
    BookOpen, 
    Layers, 
    Users, 
    Award, 
    CheckCircle2, 
    ArrowRight, 
    Sparkles, 
    Clock, 
    ShieldCheck 
} from 'lucide-react';

export default function CoursesIndex({ courses = [], filters = {} }) {
    const [search, setSearch] = useState(filters.q || '');

    const handleSearch = (e) => {
        e.preventDefault();
        router.get('/courses', { q: search }, { preserveState: true, replace: true });
    };

    return (
        <AppLayout>
            <Head title="Katalog Kursus - Saladdin Marifi LMS" />

            {/* Hero Section */}
            <div className="relative rounded-3xl overflow-hidden bg-gradient-to-b from-slate-900 via-slate-900/90 to-slate-950 border border-slate-800 p-8 sm:p-12 mb-12 shadow-2xl">
                {/* Background ambient orbs */}
                <div className="absolute top-0 right-0 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none" />
                <div className="absolute bottom-0 left-10 w-72 h-72 bg-teal-500/10 rounded-full blur-3xl pointer-events-none" />

                <div className="relative z-10 max-w-3xl">
                    <div className="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-950/80 border border-emerald-500/30 text-emerald-400 text-xs font-semibold uppercase tracking-wider mb-4">
                        <Sparkles className="w-3.5 h-3.5" />
                        Akademi Digital Saladdin Marifi
                    </div>

                    <h1 className="text-3xl sm:text-5xl font-extrabold text-white tracking-tight leading-tight mb-4">
                        Wadah Belajar Ilmu Syar'i & Tsaqafah Islam Terstruktur
                    </h1>

                    <p className="text-base sm:text-lg text-slate-300 mb-8 leading-relaxed">
                        Perdalam pemahaman agama melalui materi video interaktif, modul bacaan ilmiah, ujian pemahaman (kuis), dan raih sertifikat digital terverifikasi.
                    </p>

                    {/* Search Form */}
                    <form onSubmit={handleSearch} className="flex flex-col sm:flex-row gap-2 max-w-xl">
                        <div className="relative flex-1">
                            <Search className="w-5 h-5 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500" />
                            <input
                                type="text"
                                value={search}
                                onChange={(e) => setSearch(e.target.value)}
                                placeholder="Cari topik kursus, ustadz, atau fiqih..."
                                className="w-full pl-11 pr-4 py-3 rounded-xl bg-slate-950/90 border border-slate-700/80 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 text-sm shadow-inner"
                            />
                        </div>
                        <button
                            type="submit"
                            className="px-6 py-3 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-sm transition-all shadow-lg shadow-emerald-500/20 shrink-0"
                        >
                            Cari Kursus
                        </button>
                    </form>

                    {/* Feature Pills */}
                    <div className="mt-8 flex flex-wrap gap-4 text-xs font-medium text-slate-300">
                        <div className="flex items-center gap-1.5 bg-slate-950/60 px-3 py-1.5 rounded-lg border border-slate-800">
                            <CheckCircle2 className="w-4 h-4 text-emerald-400" />
                            Kurikulum Berurutan
                        </div>
                        <div className="flex items-center gap-1.5 bg-slate-950/60 px-3 py-1.5 rounded-lg border border-slate-800">
                            <Award className="w-4 h-4 text-amber-400" />
                            Sertifikat Resmi Terverifikasi
                        </div>
                        <div className="flex items-center gap-1.5 bg-slate-950/60 px-3 py-1.5 rounded-lg border border-slate-800">
                            <ShieldCheck className="w-4 h-4 text-teal-400" />
                            Akses 100% Gratis
                        </div>
                    </div>
                </div>
            </div>

            {/* Courses Section Header */}
            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h2 className="text-2xl font-bold text-white tracking-tight">
                        Daftar Kursus Unggulan
                    </h2>
                    <p className="text-sm text-slate-400 mt-1">
                        Pilih program belajar dan mulai tingkatkan wawasan keislaman Anda
                    </p>
                </div>
                <div className="text-xs text-slate-400 bg-slate-900 border border-slate-800 px-3 py-1.5 rounded-lg">
                    Menampilkan <span className="font-bold text-emerald-400">{courses.length}</span> Kursus
                </div>
            </div>

            {/* Courses Grid */}
            {courses.length === 0 ? (
                <div className="text-center py-16 px-4 bg-slate-900/50 rounded-2xl border border-slate-800">
                    <BookOpen className="w-12 h-12 text-slate-600 mx-auto mb-3" />
                    <h3 className="text-lg font-semibold text-slate-300">Tidak ada kursus ditemukan</h3>
                    <p className="text-sm text-slate-500 mt-1">Coba kata kunci lain atau bersihkan pencarian Anda.</p>
                </div>
            ) : (
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {courses.map((course) => (
                        <div
                            key={course.id}
                            className="group flex flex-col rounded-2xl bg-slate-900/80 border border-slate-800 hover:border-emerald-500/40 transition-all duration-300 hover:shadow-xl hover:shadow-emerald-500/5 hover:-translate-y-1 overflow-hidden"
                        >
                            {/* Card Image / Banner */}
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
                                    className="w-full h-full bg-gradient-to-tr from-slate-900 via-emerald-950 to-slate-900 flex items-center justify-center p-6 text-center"
                                    style={{ display: course.thumbnail ? 'none' : 'flex' }}
                                >
                                    <span className="text-base font-bold text-emerald-300 font-arabic">
                                        {course.title}
                                    </span>
                                </div>

                                {/* Badges */}
                                <div className="absolute top-3 left-3 flex gap-2">
                                    <span className="px-2.5 py-1 rounded-md text-[11px] font-bold uppercase tracking-wider bg-emerald-500/90 text-slate-950 shadow-md">
                                        {course.price === 0 ? 'GRATIS' : `Rp ${course.price.toLocaleString('id-ID')}`}
                                    </span>
                                </div>

                                {course.is_enrolled && (
                                    <div className="absolute top-3 right-3">
                                        <span className="px-2.5 py-1 rounded-md text-[11px] font-bold bg-slate-950/80 backdrop-blur-md text-emerald-400 border border-emerald-500/40 flex items-center gap-1 shadow">
                                            <CheckCircle2 className="w-3 h-3 text-emerald-400" /> Terdaftar
                                        </span>
                                    </div>
                                )}
                            </div>

                            {/* Card Content */}
                            <div className="p-5 flex-1 flex flex-col justify-between">
                                <div>
                                    <div className="text-xs text-emerald-400 font-semibold mb-1">
                                        Oleh: {course.instructor_name || 'Pengajar Saladdin'}
                                    </div>
                                    <h3 className="text-lg font-bold text-white group-hover:text-emerald-300 transition-colors line-clamp-2 mb-2">
                                        {course.title}
                                    </h3>
                                    <p className="text-xs text-slate-400 line-clamp-2 leading-relaxed mb-4">
                                        {course.description || 'Pelajari materi ini secara runtut dan lengkap.'}
                                    </p>
                                </div>

                                {/* Meta Stats */}
                                <div className="pt-4 border-t border-slate-800/80">
                                    <div className="grid grid-cols-2 gap-2 text-xs text-slate-400 mb-4">
                                        <div className="flex items-center gap-1.5">
                                            <Layers className="w-3.5 h-3.5 text-slate-500" />
                                            <span>{course.sections_count} Modul</span>
                                        </div>
                                        <div className="flex items-center gap-1.5">
                                            <BookOpen className="w-3.5 h-3.5 text-slate-500" />
                                            <span>{course.lessons_count} Pelajaran</span>
                                        </div>
                                    </div>

                                    {/* Progress Bar (if enrolled) */}
                                    {course.is_enrolled && (
                                        <div className="mb-4">
                                            <div className="flex justify-between text-[11px] font-semibold text-slate-400 mb-1">
                                                <span>Progres Belajar</span>
                                                <span className="text-emerald-400">{course.progress}%</span>
                                            </div>
                                            <div className="w-full h-2 rounded-full bg-slate-800 overflow-hidden">
                                                <div 
                                                    className="h-full bg-gradient-to-r from-emerald-500 to-teal-400 rounded-full transition-all duration-500"
                                                    style={{ width: `${course.progress}%` }}
                                                />
                                            </div>
                                        </div>
                                    )}

                                    {/* Action Button */}
                                    <Link
                                        href={`/courses/${course.slug}`}
                                        className="w-full py-2.5 px-4 rounded-xl text-xs font-bold text-center flex items-center justify-center gap-2 transition-all bg-slate-800 hover:bg-emerald-500 hover:text-slate-950 text-white group-hover:bg-emerald-500 group-hover:text-slate-950"
                                    >
                                        <span>{course.is_enrolled ? 'Lanjutkan Belajar' : 'Lihat Detail Kursus'}</span>
                                        <ArrowRight className="w-3.5 h-3.5" />
                                    </Link>
                                </div>
                            </div>
                        </div>
                    ))}
                </div>
            )}
        </AppLayout>
    );
}
