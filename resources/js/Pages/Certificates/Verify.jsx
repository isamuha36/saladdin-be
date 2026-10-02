import React from 'react';
import { Head, Link } from '@inertiajs/react';
import AppLayout from '../../Layouts/AppLayout';
import { 
    ShieldCheck, 
    CheckCircle2, 
    XCircle, 
    Award, 
    Calendar, 
    User, 
    BookOpen, 
    ArrowLeft 
} from 'lucide-react';

export default function CertificateVerify({ verification, certificateNumber }) {
    const isValid = verification?.valid;

    return (
        <AppLayout>
            <Head title={`Verifikasi Sertifikat - ${certificateNumber}`} />

            <div className="max-w-2xl mx-auto py-8">
                
                <div className="mb-6">
                    <Link
                        href="/"
                        className="inline-flex items-center gap-2 text-xs font-semibold text-slate-400 hover:text-white transition-colors"
                    >
                        <ArrowLeft className="w-4 h-4" />
                        <span>Kembali ke Beranda</span>
                    </Link>
                </div>

                <div className="bg-slate-900/95 border border-slate-800 rounded-3xl p-6 sm:p-10 shadow-2xl relative overflow-hidden">
                    
                    {isValid ? (
                        <div className="space-y-6">
                            
                            {/* Verified Seal Header */}
                            <div className="text-center space-y-3 pb-6 border-b border-slate-800">
                                <div className="w-16 h-16 rounded-full bg-emerald-500/20 border-2 border-emerald-500/40 text-emerald-400 flex items-center justify-center mx-auto shadow-xl shadow-emerald-500/20">
                                    <ShieldCheck className="w-9 h-9" />
                                </div>
                                <h1 className="text-2xl font-extrabold text-white">
                                    Sertifikat Resmi & Sah
                                </h1>
                                <p className="text-xs text-emerald-400 font-semibold tracking-wide uppercase">
                                    Terotentikasi di Sistem Saladdin Marifi LMS
                                </p>
                            </div>

                            {/* Verification Data Table */}
                            <div className="space-y-4">
                                <div className="p-4 rounded-xl bg-slate-950/70 border border-slate-800 flex items-center justify-between">
                                    <span className="text-xs text-slate-400">Nomor Registrasi</span>
                                    <span className="font-mono text-xs font-bold text-amber-400">{verification.certificate_number}</span>
                                </div>

                                <div className="p-4 rounded-xl bg-slate-950/70 border border-slate-800 flex items-center justify-between">
                                    <span className="text-xs text-slate-400">Penerima Sertifikat</span>
                                    <span className="text-xs font-bold text-white">{verification.student_name}</span>
                                </div>

                                <div className="p-4 rounded-xl bg-slate-950/70 border border-slate-800 flex items-center justify-between">
                                    <span className="text-xs text-slate-400">Program / Kursus</span>
                                    <span className="text-xs font-bold text-emerald-300 text-right max-w-xs">{verification.course_title}</span>
                                </div>

                                <div className="p-4 rounded-xl bg-slate-950/70 border border-slate-800 flex items-center justify-between">
                                    <span className="text-xs text-slate-400">Tanggal Kelulusan</span>
                                    <span className="text-xs font-semibold text-slate-200">{verification.issued_at}</span>
                                </div>

                                <div className="p-4 rounded-xl bg-slate-950/70 border border-slate-800 flex items-center justify-between">
                                    <span className="text-xs text-slate-400">Pemateri Kursus</span>
                                    <span className="text-xs font-semibold text-slate-200">{verification.instructor || 'Ustadz Saladdin'}</span>
                                </div>
                            </div>

                            <div className="pt-4 text-center text-xs text-slate-500 leading-relaxed">
                                Keaslian sertifikat ini dijamin dan tercatat dalam pangkalan data resmi Saladdin Marifi LMS.
                            </div>

                        </div>
                    ) : (
                        <div className="text-center py-8 space-y-4">
                            <div className="w-16 h-16 rounded-full bg-rose-500/20 border-2 border-rose-500/40 text-rose-400 flex items-center justify-center mx-auto shadow-xl">
                                <XCircle className="w-9 h-9" />
                            </div>
                            <h1 className="text-2xl font-bold text-white">
                                Sertifikat Tidak Ditemukan
                            </h1>
                            <p className="text-xs text-slate-400 max-w-md mx-auto">
                                Nomor sertifikat <span className="font-mono text-rose-400 font-bold">{certificateNumber}</span> tidak terdaftar dalam basis data Saladdin Marifi atau telah dicabut.
                            </p>
                            <div className="pt-4">
                                <Link
                                    href="/courses"
                                    className="px-6 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-semibold text-xs inline-block"
                                >
                                    Lihat Kursus Resmi
                                </Link>
                            </div>
                        </div>
                    )}

                </div>

            </div>
        </AppLayout>
    );
}
