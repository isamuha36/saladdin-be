import React, { useState } from 'react';
import { Link, usePage, router } from '@inertiajs/react';
import { 
    Menu, 
    Search, 
    Sparkles, 
    GraduationCap, 
    LogIn, 
    UserPlus, 
    ShieldCheck, 
    ChevronDown, 
    LogOut,
    LayoutDashboard,
    Award
} from 'lucide-react';

export default function TopHeader({ onOpenSidebar }) {
    const { auth, url } = usePage().props;
    const user = auth?.user;
    const [profileDropdownOpen, setProfileDropdownOpen] = useState(false);

    const handleLogout = (e) => {
        e.preventDefault();
        router.post('/logout');
    };

    // Determine simple breadcrumb / page title
    const getPageTitle = () => {
        if (url === '/' || url.startsWith('/courses')) return 'Katalog Kursus';
        if (url.startsWith('/dashboard')) return 'Dashboard Belajar';
        if (url.startsWith('/my-certificates') || url.startsWith('/certificates')) return 'Sertifikat Digital';
        if (url.includes('verify-certificate')) return 'Verifikasi Sertifikat';
        return 'Saladdin Marifi LMS';
    };

    return (
        <header className="sticky top-0 z-30 h-16 sm:h-20 bg-slate-950/70 backdrop-blur-md border-b border-slate-800/80 px-4 sm:px-6 lg:px-8 flex items-center justify-between transition-colors">
            
            {/* Left: Mobile Sidebar Toggle & Page Title */}
            <div className="flex items-center gap-3 sm:gap-4">
                <button
                    onClick={onOpenSidebar}
                    className="lg:hidden p-2 rounded-xl text-slate-300 hover:text-white bg-slate-900 border border-slate-800 hover:border-slate-700 transition-colors"
                    aria-label="Buka Menu Sidebar"
                >
                    <Menu className="w-5 h-5 text-emerald-400" />
                </button>

                <div className="flex items-center gap-2">
                    <span className="text-sm sm:text-base font-bold text-white tracking-tight">
                        {getPageTitle()}
                    </span>
                    <span className="hidden sm:inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                        Islamic LMS
                    </span>
                </div>
            </div>

            {/* Right: Quick actions & Profile */}
            <div className="flex items-center gap-2 sm:gap-3">
                {user ? (
                    <div className="relative">
                        <button
                            onClick={() => setProfileDropdownOpen(!profileDropdownOpen)}
                            className="flex items-center gap-2.5 p-1.5 sm:px-3 sm:py-1.5 rounded-xl bg-slate-900/90 border border-slate-800 hover:border-emerald-500/40 text-left transition-all"
                        >
                            <div className="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center font-bold text-slate-950 text-sm shadow">
                                {user.name.charAt(0).toUpperCase()}
                            </div>
                            <div className="hidden sm:flex flex-col pr-1">
                                <span className="text-xs font-semibold text-slate-200 line-clamp-1 max-w-[130px]">
                                    {user.name}
                                </span>
                                <span className="text-[10px] text-emerald-400 font-medium capitalize flex items-center gap-1">
                                    {user.role === 'admin' ? (
                                        <>
                                            <ShieldCheck className="w-2.5 h-2.5" /> Admin
                                        </>
                                    ) : (
                                        'Siswa'
                                    )}
                                </span>
                            </div>
                            <ChevronDown className="hidden sm:block w-3.5 h-3.5 text-slate-400" />
                        </button>

                        {/* Dropdown Menu */}
                        {profileDropdownOpen && (
                            <div 
                                className="absolute right-0 mt-2 w-56 rounded-xl bg-slate-900 border border-slate-800 shadow-2xl py-2 z-50 text-sm animate-in fade-in zoom-in-95 duration-100"
                                onClick={() => setProfileDropdownOpen(false)}
                            >
                                <div className="px-4 py-2 border-b border-slate-800">
                                    <p className="text-xs text-slate-400">Masuk sebagai</p>
                                    <p className="font-semibold text-white truncate">{user.email}</p>
                                </div>
                                <Link
                                    href="/dashboard"
                                    className="w-full px-4 py-2.5 text-slate-300 hover:text-white hover:bg-slate-800/60 flex items-center gap-2.5 transition-colors"
                                >
                                    <LayoutDashboard className="w-4 h-4 text-emerald-400" />
                                    Dashboard Belajar
                                </Link>
                                <Link
                                    href="/my-certificates"
                                    className="w-full px-4 py-2.5 text-slate-300 hover:text-white hover:bg-slate-800/60 flex items-center gap-2.5 transition-colors"
                                >
                                    <Award className="w-4 h-4 text-amber-400" />
                                    Sertifikat Saya
                                </Link>
                                
                                <div className="my-1 border-t border-slate-800" />
                                
                                <button
                                    onClick={handleLogout}
                                    className="w-full text-left px-4 py-2.5 text-rose-400 hover:text-rose-300 hover:bg-rose-500/10 flex items-center gap-2.5 transition-colors"
                                >
                                    <LogOut className="w-4 h-4" />
                                    Keluar (Logout)
                                </button>
                            </div>
                        )}
                    </div>
                ) : (
                    <div className="flex items-center gap-2">
                        <a
                            href="/demo-login/student"
                            className="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-950/60 text-emerald-400 border border-emerald-500/30 hover:bg-emerald-900/60 transition-colors"
                            title="Masuk langsung dengan akun Siswa demo"
                        >
                            <Sparkles className="w-3.5 h-3.5" />
                            Demo Siswa
                        </a>
                        <Link
                            href="/login"
                            className="px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg text-xs sm:text-sm font-medium text-slate-300 hover:text-white hover:bg-slate-900 transition-colors"
                        >
                            Masuk
                        </Link>
                        <Link
                            href="/register"
                            className="px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg text-xs sm:text-sm font-semibold text-slate-950 bg-emerald-400 hover:bg-emerald-300 shadow-md shadow-emerald-500/20 transition-all"
                        >
                            Daftar
                        </Link>
                    </div>
                )}
            </div>

        </header>
    );
}
