import React from 'react';
import { Head, Link } from '@inertiajs/react';
import AppLayout from '../../Layouts/AppLayout';
import { 
    Award, 
    Download, 
    ShieldCheck, 
    ArrowLeft, 
    Calendar, 
    User, 
    CheckCircle2, 
    Printer 
} from 'lucide-react';

export default function CertificateShow({ certificate }) {
    return (
        <AppLayout>
            <Head title={`Sertifikat - ${certificate.certificate_number}`} />

            <div className="max-w-4xl mx-auto space-y-6">
                
                {/* Back Link & Actions */}
                <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <Link
                        href="/my-certificates"
                        className="inline-flex items-center gap-2 text-xs font-semibold text-slate-400 hover:text-white transition-colors"
                    >
                        <ArrowLeft className="w-4 h-4" />
                        <span>Kembali ke Koleksi Sertifikat</span>
                    </Link>

                    <div className="flex items-center gap-3">
                        <Link
                            href={`/verify-certificate/${certificate.certificate_number}`}
                            className="px-4 py-2 rounded-xl bg-slate-900 border border-slate-800 hover:border-slate-700 text-emerald-400 text-xs font-semibold flex items-center gap-1.5 transition-colors"
                        >
                            <ShieldCheck className="w-4 h-4" />
                            Halaman Verifikasi
                        </Link>
                        <a
                            href={`/certificates/${certificate.id}/download`}
                            className="px-5 py-2 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-bold flex items-center gap-1.5 shadow-lg shadow-amber-500/20 transition-all hover:scale-105"
                        >
                            <Download className="w-4 h-4" />
                            Unduh PDF Resmi
                        </a>
                    </div>
                </div>

                {/* Printable Certificate Canvas / Card */}
                <div className="bg-slate-900/95 border-2 border-amber-500/40 rounded-3xl p-8 sm:p-14 shadow-2xl relative overflow-hidden text-center">
                    
                    {/* Inner ornamental decorative line */}
                    <div className="absolute inset-3 border border-amber-500/20 rounded-2xl pointer-events-none" />

                    <div className="relative z-10 space-y-6">
                        
                        {/* Emblem */}
                        <div className="w-16 h-16 rounded-2xl bg-amber-500/10 border-2 border-amber-500/40 text-amber-400 flex items-center justify-center mx-auto shadow-xl">
                            <Award className="w-9 h-9" />
                        </div>

                        <div>
                            <div className="text-xs uppercase font-extrabold tracking-[0.25em] text-amber-400 mb-1">
                                SALADDIN MARIFI ISLAMIC ACADEMY
                            </div>
                            <h1 className="text-2xl sm:text-4xl font-extrabold text-white tracking-tight font-serif">
                                SERTIFIKAT KELULUSAN
                            </h1>
                            <div className="text-sm text-emerald-400 font-arabic italic mt-1">
                                شَهَادَةُ إِتْمَامِ الدِّرَاسَةِ
                            </div>
                        </div>

                        <div className="text-xs text-slate-400 uppercase tracking-widest">
                            Diberikan dengan penuh kehormatan kepada:
                        </div>

                        {/* Student Name */}
                        <div className="py-2">
                            <div className="text-2xl sm:text-3xl font-extrabold text-white tracking-wide border-b border-amber-500/30 pb-2 inline-block min-w-[280px]">
                                {certificate.student_name}
                            </div>
                        </div>

                        <p className="text-xs sm:text-sm text-slate-300 max-w-xl mx-auto leading-relaxed">
                            Telah berhasil menuntaskan seluruh modul kurikulum, evaluasi pemahaman, serta lulus ujian akhir dengan hasil yang memuaskan pada program:
                        </p>

                        {/* Course Title */}
                        <div className="text-lg sm:text-xl font-bold text-amber-300">
                            "{certificate.course.title}"
                        </div>

                        {/* Metadata row */}
                        <div className="pt-8 border-t border-slate-800 grid grid-cols-1 sm:grid-cols-3 gap-6 text-xs text-slate-400">
                            <div>
                                <div className="text-slate-500 text-[10px] uppercase font-bold tracking-wider">Nomor Sertifikat</div>
                                <div className="font-mono text-white font-semibold mt-0.5">{certificate.certificate_number}</div>
                            </div>
                            <div>
                                <div className="text-slate-500 text-[10px] uppercase font-bold tracking-wider">Tanggal Diterbitkan</div>
                                <div className="text-white font-semibold mt-0.5">{certificate.issued_at}</div>
                            </div>
                            <div>
                                <div className="text-slate-500 text-[10px] uppercase font-bold tracking-wider">Status Validasi</div>
                                <div className="text-emerald-400 font-semibold mt-0.5 flex items-center justify-center gap-1">
                                    <CheckCircle2 className="w-3.5 h-3.5" /> Terverifikasi Sistem
                                </div>
                            </div>
                        </div>

                        {/* Signatures */}
                        {certificate.signatures?.length > 0 && (
                            <div className="pt-6 flex flex-wrap items-center justify-center gap-10">
                                {certificate.signatures.map((sig, idx) => (
                                    <div key={idx} className="text-center">
                                        <div className="h-12 flex items-center justify-center text-xs font-serif italic text-amber-300">
                                            {sig.signature_url ? (
                                                <img src={sig.signature_url} alt={sig.name} className="h-10 object-contain mx-auto" />
                                            ) : (
                                                <span>(Tanda Tangan Digital)</span>
                                            )}
                                        </div>
                                        <div className="font-bold text-xs text-white border-t border-slate-700 pt-1">
                                            {sig.name}
                                        </div>
                                        <div className="text-[10px] text-slate-400">{sig.title}</div>
                                    </div>
                                ))}
                            </div>
                        )}

                    </div>
                </div>

            </div>
        </AppLayout>
    );
}
