import React, { useState } from 'react';
import { Link, usePage, router } from '@inertiajs/react';
import { 
    BookOpen, 
    GraduationCap, 
    Award, 
    LayoutDashboard, 
    LogOut, 
    LogIn, 
    UserPlus, 
    Menu, 
    X, 
    ChevronDown, 
    Sparkles, 
    ShieldCheck 
} from 'lucide-react';

export default function Navbar() {
    const { auth } = usePage().props;
    const user = auth?.user;
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
    const [profileDropdownOpen, setProfileDropdownOpen] = useState(false);

    const handleLogout = (e) => {
        e.preventDefault();
        router.post('/logout');
    };

    return (
        <header className="sticky top-0 z-50 bg-slate-950/80 backdrop-blur-md border-b border-slate-800/80">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="flex items-center justify-between h-16 sm:h-20">
                    
                    {/* Logo & Brand */}
                    <div className="flex items-center gap-8">
                        <Link href="/" className="flex items-center gap-3 group">
                            <div className="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-600 via-emerald-500 to-teal-400 flex items-center justify-center shadow-lg shadow-emerald-500/20 group-hover:scale-105 transition-transform duration-200">
                                <GraduationCap className="w-6 h-6 text-slate-950 font-bold" />
                            </div>
                            <div className="flex flex-col">
                                <span className="text-lg font-bold tracking-tight text-white group-hover:text-emerald-400 transition-colors">
                                    SALADDIN <span className="text-emerald-400 font-extrabold">MARIFI</span>
                                </span>
                                <span className="text-[10px] text-slate-400 uppercase tracking-widest font-semibold">
                                    Islamic Learning Academy
                                </span>
                            </div>
                        </Link>

                        {/* Desktop Navigation Links */}
                        <nav className="hidden md:flex items-center gap-1">
                            <Link
                                href="/courses"
                                className="px-3.5 py-2 rounded-lg text-sm font-medium text-slate-300 hover:text-white hover:bg-slate-900 transition-colors flex items-center gap-2"
                            >
                                <BookOpen className="w-4 h-4 text-emerald-400" />
                                Katalog Kursus
                            </Link>

                            {user && (
                                <>
                                    <Link
                                        href="/dashboard"
                                        className="px-3.5 py-2 rounded-lg text-sm font-medium text-slate-300 hover:text-white hover:bg-slate-900 transition-colors flex items-center gap-2"
                                    >
                                        <LayoutDashboard className="w-4 h-4 text-emerald-400" />
                                        Dashboard
                                    </Link>
                                    <Link
                                        href="/my-certificates"
                                        className="px-3.5 py-2 rounded-lg text-sm font-medium text-slate-300 hover:text-white hover:bg-slate-900 transition-colors flex items-center gap-2"
                                    >
                                        <Award className="w-4 h-4 text-amber-400" />
                                        Sertifikat Saya
                                    </Link>
                                </>
                            )}
                        </nav>
                    </div>

                    {/* Right side actions */}
                    <div className="hidden md:flex items-center gap-3">
                        {user ? (
                            <div className="relative">
                                <button
                                    onClick={() => setProfileDropdownOpen(!profileDropdownOpen)}
                                    className="flex items-center gap-3 px-3 py-1.5 rounded-full bg-slate-900 border border-slate-800 hover:border-emerald-500/40 text-left transition-all"
                                >
                                    <div className="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center font-bold text-slate-950 text-sm shadow">
                                        {user.name.charAt(0).toUpperCase()}
                                    </div>
                                    <div className="flex flex-col pr-1">
                                        <span className="text-xs font-semibold text-slate-200 line-clamp-1 max-w-[120px]">
                                            {user.name}
                                        </span>
                                        <span className="text-[10px] text-emerald-400 flex items-center gap-1 font-medium capitalize">
                                            {user.role === 'admin' ? (
                                                <>
                                                    <ShieldCheck className="w-2.5 h-2.5" /> Admin
                                                </>
                                            ) : (
                                                'Siswa'
                                            )}
                                        </span>
                                    </div>
                                    <ChevronDown className="w-3.5 h-3.5 text-slate-400" />
                                </button>

                                {/* Dropdown */}
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
                                    className="hidden lg:flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-950/60 text-emerald-400 border border-emerald-500/30 hover:bg-emerald-900/60 transition-colors"
                                    title="Masuk langsung dengan akun Siswa demo"
                                >
                                    <Sparkles className="w-3.5 h-3.5" />
                                    Demo Siswa
                                </a>
                                <Link
                                    href="/login"
                                    className="px-4 py-2 rounded-lg text-sm font-medium text-slate-300 hover:text-white hover:bg-slate-900 transition-colors flex items-center gap-2"
                                >
                                    <LogIn className="w-4 h-4 text-slate-400" />
                                    Masuk
                                </Link>
                                <Link
                                    href="/register"
                                    className="px-4 py-2 rounded-lg text-sm font-semibold text-slate-950 bg-emerald-400 hover:bg-emerald-300 shadow-md shadow-emerald-500/20 transition-all flex items-center gap-1.5 hover:scale-[1.02]"
                                >
                                    <UserPlus className="w-4 h-4" />
                                    Daftar Akun
                                </Link>
                            </div>
                        )}
                    </div>

                    {/* Mobile Hamburger Button */}
                    <div className="flex md:hidden items-center gap-2">
                        <button
                            onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
                            className="p-2 rounded-lg bg-slate-900 text-slate-300 hover:text-white border border-slate-800 focus:outline-none"
                        >
                            {mobileMenuOpen ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
                        </button>
                    </div>

                </div>
            </div>

            {/* Mobile Navigation Dropdown */}
            {mobileMenuOpen && (
                <div className="md:hidden border-b border-slate-800 bg-slate-950/95 backdrop-blur-xl px-4 pt-3 pb-6 space-y-3">
                    <nav className="space-y-1">
                        <Link
                            href="/courses"
                            onClick={() => setMobileMenuOpen(false)}
                            className="w-full px-3 py-2.5 rounded-lg text-base font-medium text-slate-200 hover:bg-slate-900 flex items-center gap-3"
                        >
                            <BookOpen className="w-5 h-5 text-emerald-400" />
                            Katalog Kursus
                        </Link>
                        {user && (
                            <>
                                <Link
                                    href="/dashboard"
                                    onClick={() => setMobileMenuOpen(false)}
                                    className="w-full px-3 py-2.5 rounded-lg text-base font-medium text-slate-200 hover:bg-slate-900 flex items-center gap-3"
                                >
                                    <LayoutDashboard className="w-5 h-5 text-emerald-400" />
                                    Dashboard
                                </Link>
                                <Link
                                    href="/my-certificates"
                                    onClick={() => setMobileMenuOpen(false)}
                                    className="w-full px-3 py-2.5 rounded-lg text-base font-medium text-slate-200 hover:bg-slate-900 flex items-center gap-3"
                                >
                                    <Award className="w-5 h-5 text-amber-400" />
                                    Sertifikat Saya
                                </Link>
                            </>
                        )}
                    </nav>

                    <div className="pt-3 border-t border-slate-800 space-y-2">
                        {user ? (
                            <div className="space-y-2">
                                <div className="px-3 py-2 rounded-lg bg-slate-900/60 border border-slate-800 text-sm">
                                    <div className="font-semibold text-white">{user.name}</div>
                                    <div className="text-xs text-slate-400">{user.email}</div>
                                </div>
                                <button
                                    onClick={handleLogout}
                                    className="w-full py-2.5 px-3 rounded-lg text-sm font-semibold text-rose-400 bg-rose-500/10 border border-rose-500/20 flex items-center justify-center gap-2"
                                >
                                    <LogOut className="w-4 h-4" /> Keluar (Logout)
                                </button>
                            </div>
                        ) : (
                            <div className="grid grid-cols-2 gap-2">
                                <Link
                                    href="/login"
                                    onClick={() => setMobileMenuOpen(false)}
                                    className="py-2.5 text-center rounded-lg text-sm font-medium bg-slate-900 text-white border border-slate-800"
                                >
                                    Masuk
                                </Link>
                                <Link
                                    href="/register"
                                    onClick={() => setMobileMenuOpen(false)}
                                    className="py-2.5 text-center rounded-lg text-sm font-semibold bg-emerald-400 text-slate-950 shadow-md shadow-emerald-500/20"
                                >
                                    Daftar
                                </Link>
                            </div>
                        )}
                    </div>
                </div>
            )}
        </header>
    );
}
