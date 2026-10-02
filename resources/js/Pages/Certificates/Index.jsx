import React from 'react';
import { Head, Link } from '@inertiajs/react';
import AppLayout from '../../Layouts/AppLayout';
import { 
    Award, 
    Download, 
    ShieldCheck, 
    ExternalLink, 
    Calendar, 
    BookOpen, 
    Sparkles 
} from 'lucide-react';

export default function CertificatesIndex({ certificates = [] }) {
    return (
        <AppLayout>
            <Head title="Sertifikat Saya - Saladdin Marifi LMS" />

            {/* Header */}
            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                <div>
                    <div className="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-500/10 text-amber-400 text-xs font-semibold mb-2">
                        <Sparkles className="w-3.5 h-3.5" />
                        Pencapaian Belajar Anda
                    </div>
                    <h1 className="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                        Koleksi Sertifikat Digital
                    </h1>
                    <p className="text-xs sm:text-sm text-slate-400 mt-1">
                        Sertifikat resmi yang diterbitkan setelah Anda menuntaskan seluruh materi kursus dan lulus kuis
                    </p>
                </div>
            </div>

            {/* List or Empty State */}
            {certificates.length === 0 ? (
                <div className="text-center py-20 px-4 bg-slate-900/50 rounded-3xl border border-slate-800 max-w-2xl mx-auto">
                    <div className="w-16 h-16 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center mx-auto mb-4">
                        <Award className="w-8 h-8" />
                    </div>
                    <h3 className="text-lg font-bold text-white mb-2">Belum Ada Sertifikat yang Diterbitkan</h3>
                    <p className="text-xs sm:text-sm text-slate-400 leading-relaxed mb-6 max-w-md mx-auto">
                        Selesaikan pembelajaran hingga 100% dan lulus ujian kuis di setiap kursus untuk otomatis memperoleh sertifikat resmi.
                    </p>
                    <Link
                        href="/courses"
                        className="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs shadow-lg shadow-emerald-500/20 transition-all hover:scale-105"
                    >
                        <BookOpen className="w-4 h-4" />
                        Mulai Belajar Kursus
                    </Link>
                </div>
            ) : (
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {certificates.map((cert) => (
                        <div
                            key={cert.id}
                            className="bg-slate-900/90 border border-amber-500/30 rounded-2xl p-6 shadow-xl relative overflow-hidden flex flex-col justify-between group hover:border-amber-500/60 transition-all hover:-translate-y-1"
                        >
                            {/* Ambient glow */}
                            <div className="absolute top-0 right-0 w-32 h-32 bg-amber-500/10 rounded-full blur-2xl pointer-events-none" />

                            <div>
                                <div className="flex items-center justify-between mb-4">
                                    <div className="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center">
                                        <Award className="w-5 h-5" />
                                    </div>
                                    <span className="text-[10px] font-mono font-semibold text-slate-400 bg-slate-950/80 px-2.5 py-1 rounded-md border border-slate-800">
                                        {cert.certificate_number}
                                    </span>
                                </div>

                                <h3 className="text-lg font-bold text-white group-hover:text-amber-300 transition-colors line-clamp-2 mb-2">
                                    {cert.course_title}
                                </h3>

                                <div className="space-y-1.5 text-xs text-slate-400 mb-6">
                                    <div>Pengajar: <span className="text-slate-300">{cert.instructor || 'Ustadz Saladdin'}</span></div>
                                    <div className="flex items-center gap-1.5">
                                        <Calendar className="w-3.5 h-3.5 text-slate-500" />
                                        <span>Diterbitkan: {cert.issued_at}</span>
                                    </div>
                                </div>
                            </div>

                            {/* Actions */}
                            <div className="pt-4 border-t border-slate-800 space-y-2">
                                <a
                                    href={`/certificates/${cert.id}/download`}
                                    className="w-full py-2.5 px-3 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs flex items-center justify-center gap-2 shadow-md shadow-amber-500/10 transition-colors"
                                >
                                    <Download className="w-4 h-4" />
                                    <span>Unduh Dokumen PDF</span>
                                </a>

                                <div className="grid grid-cols-2 gap-2">
                                    <Link
                                        href={`/certificates/${cert.id}`}
                                        className="py-2 px-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-semibold text-[11px] text-center transition-colors"
                                    >
                                        Pratinjau
                                    </Link>
                                    <Link
                                        href={`/verify-certificate/${cert.certificate_number}`}
                                        className="py-2 px-3 rounded-xl bg-slate-950 border border-slate-800 hover:border-slate-700 text-emerald-400 font-semibold text-[11px] text-center flex items-center justify-center gap-1 transition-colors"
                                    >
                                        <ShieldCheck className="w-3 h-3" />
                                        Verifikasi
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
