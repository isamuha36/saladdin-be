import React from 'react';
import { Link } from '@inertiajs/react';
import { GraduationCap, ShieldCheck, Heart } from 'lucide-react';

export default function Footer() {
    return (
        <footer className="border-t border-slate-800/80 bg-slate-950 text-slate-400 py-12 mt-auto">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                    
                    {/* Brand */}
                    <div className="md:col-span-2 space-y-3">
                        <div className="flex items-center gap-3">
                            <div className="w-8 h-8 rounded-lg bg-emerald-500 flex items-center justify-center text-slate-950 font-bold">
                                <GraduationCap className="w-5 h-5" />
                            </div>
                            <span className="font-bold text-white text-base tracking-tight">
                                SALADDIN <span className="text-emerald-400">MARIFI</span>
                            </span>
                        </div>
                        <p className="text-sm text-slate-400 max-w-md leading-relaxed">
                            Platform pembelajaran digital Islam terstruktur, menyajikan materi fikih, tafsir, sirah, dan sejarah Islam dengan penyampaian ilmiah dan kurikulum komprehensif.
                        </p>
                    </div>

                    {/* Navigasi */}
                    <div>
                        <h4 className="text-xs uppercase font-bold tracking-wider text-slate-200 mb-3">
                            Navigasi
                        </h4>
                        <ul className="space-y-2 text-sm">
                            <li>
                                <Link href="/courses" className="hover:text-emerald-400 transition-colors">
                                    Katalog Kursus
                                </Link>
                            </li>
                            <li>
                                <Link href="/dashboard" className="hover:text-emerald-400 transition-colors">
                                    Ruang Belajar Saya
                                </Link>
                            </li>
                            <li>
                                <Link href="/my-certificates" className="hover:text-emerald-400 transition-colors">
                                    Sertifikat Digital
                                </Link>
                            </li>
                        </ul>
                    </div>

                    {/* Keamanan & Verifikasi */}
                    <div>
                        <h4 className="text-xs uppercase font-bold tracking-wider text-slate-200 mb-3">
                            Verifikasi
                        </h4>
                        <p className="text-xs text-slate-400 mb-3 leading-relaxed">
                            Setiap sertifikat kelulusan dilengkapi QR Code dan nomor serial unik yang dapat divalidasi publik.
                        </p>
                        <div className="flex items-center gap-2 text-xs text-emerald-400 font-medium bg-emerald-950/40 border border-emerald-500/20 px-3 py-2 rounded-lg">
                            <ShieldCheck className="w-4 h-4 text-emerald-400 shrink-0" />
                            <span>Terotentikasi & Terenkripsi</span>
                        </div>
                    </div>

                </div>

                <div className="pt-8 border-t border-slate-900 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-400 gap-4">
                    <p>© {new Date().getFullYear()} Saladdin Marifi LMS. Seluruh Hak Cipta Dilindungi.</p>
                    <div className="flex items-center gap-1 text-slate-400">
                        <span>Dibangun dengan cinta untuk umat</span>
                        <Heart className="w-3.5 h-3.5 text-rose-500 inline fill-rose-500/30" />
                    </div>
                </div>
            </div>
        </footer>
    );
}
