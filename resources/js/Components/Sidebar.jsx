import React from 'react';
import { Link, usePage, router } from '@inertiajs/react';
import { 
    GraduationCap, 
    LayoutDashboard, 
    BookOpen, 
    Award, 
    ShieldCheck, 
    Sparkles, 
    LogIn, 
    UserPlus, 
    LogOut, 
    X,
    ChevronRight,
    ExternalLink
} from 'lucide-react';

export default function Sidebar({ isOpen, setIsOpen }) {
    const { auth, url } = usePage().props;
    const user = auth?.user;

    const handleLogout = (e) => {
        e.preventDefault();
        router.post('/logout');
    };

    const navItems = [
        {
            name: 'Katalog Kursus',
            href: '/courses',
            icon: BookOpen,
            active: url === '/' || url.startsWith('/courses'),
            badge: 'Katalog'
        },
        ...(user ? [
            {
                name: 'Dashboard Belajar',
                href: '/dashboard',
                icon: LayoutDashboard,
                active: url.startsWith('/dashboard'),
                badge: 'Progres'
            },
            {
                name: 'Sertifikat Saya',
                href: '/my-certificates',
                icon: Award,
                active: url.startsWith('/my-certificates') || (url.startsWith('/certificates') && !url.includes('verify')),
                badge: 'Digital'
            },
        ] : []),
        {
            name: 'Verifikasi Sertifikat',
            href: '/verify-certificate/DEMO-VERIFY',
            icon: ShieldCheck,
            active: url.includes('verify-certificate'),
            badge: 'Publik'
        }
    ];

    const sidebarContent = (
        <div className="flex flex-col h-full bg-slate-950 border-r border-slate-800/80 text-slate-100 select-none">
            
            {/* Header / Brand */}
            <div className="h-20 flex items-center justify-between px-6 border-b border-slate-800/80 bg-slate-950/60 backdrop-blur-md">
                <Link href="/" className="flex items-center gap-3 group" onClick={() => setIsOpen?.(false)}>
                    <div className="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-600 via-emerald-500 to-teal-400 flex items-center justify-center shadow-lg shadow-emerald-500/20 group-hover:scale-105 transition-transform duration-200 shrink-0">
                        <GraduationCap className="w-6 h-6 text-slate-950 font-bold" />
                    </div>
                    <div className="flex flex-col">
                        <span className="text-base font-bold tracking-tight text-white group-hover:text-emerald-400 transition-colors">
                            SALADDIN <span className="text-emerald-400 font-extrabold">MARIFI</span>
                        </span>
                        <span className="text-[10px] text-slate-400 uppercase tracking-widest font-semibold">
                            Islamic Learning Academy
                        </span>
                    </div>
                </Link>

                {/* Mobile close button */}
                {setIsOpen && (
                    <button
                        onClick={() => setIsOpen(false)}
                        className="lg:hidden p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-900 border border-slate-800"
                        aria-label="Tutup Menu"
                    >
                        <X className="w-5 h-5" />
                    </button>
                )}
            </div>

            {/* Navigation Body */}
            <div className="flex-1 overflow-y-auto px-4 py-6 space-y-6">
                
                {/* Menu Navigasi Utama */}
                <div>
                    <div className="px-3 mb-2 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        Menu Utama
                    </div>
                    <nav className="space-y-1.5">
                        {navItems.map((item) => {
                            const Icon = item.icon;
                            return (
                                <Link
                                    key={item.name}
                                    href={item.href}
                                    onClick={() => setIsOpen?.(false)}
                                    className={`group flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all ${
                                        item.active
                                            ? 'bg-gradient-to-r from-emerald-500/20 to-teal-500/10 text-emerald-300 border border-emerald-500/30 shadow-sm shadow-emerald-500/10'
                                            : 'text-slate-300 hover:text-white hover:bg-slate-900/80 border border-transparent'
                                    }`}
                                >
                                    <div className="flex items-center gap-3">
                                        <Icon className={`w-4 h-4 transition-colors ${
                                            item.active ? 'text-emerald-400' : 'text-slate-400 group-hover:text-emerald-400'
                                        }`} />
                                        <span>{item.name}</span>
                                    </div>
                                    {item.badge && (
                                        <span className={`text-[10px] px-2 py-0.5 rounded-md font-semibold ${
                                            item.active
                                                ? 'bg-emerald-500/20 text-emerald-300'
                                                : 'bg-slate-900 text-slate-400 group-hover:bg-slate-800'
                                        }`}>
                                            {item.badge}
                                        </span>
                                    )}
                                </Link>
                            );
                        })}
                    </nav>
                </div>

                {/* Quick Demo Switcher (Testing & Demo) */}
                <div className="pt-2">
                    <div className="px-3 mb-2 text-[11px] font-bold uppercase tracking-wider text-slate-500 flex items-center justify-between">
                        <span>Akses Demo Cepat</span>
                        <Sparkles className="w-3.5 h-3.5 text-amber-400" />
                    </div>
                    <div className="p-3 rounded-2xl bg-gradient-to-br from-slate-900/90 to-slate-900/40 border border-slate-800/90 space-y-2">
                        <p className="text-xs text-slate-400 leading-relaxed">
                            Coba fitur pembelajaran tanpa registrasi:
                        </p>
                        <div className="grid grid-cols-2 gap-2">
                            <a
                                href="/demo-login/student"
                                className="px-2.5 py-2 rounded-lg text-xs font-semibold text-center bg-emerald-950/70 text-emerald-300 border border-emerald-500/30 hover:bg-emerald-900/70 transition-colors flex items-center justify-center gap-1"
                            >
                                <Sparkles className="w-3 h-3 text-emerald-400" />
                                Siswa
                            </a>
                            <a
                                href="/demo-login/admin"
                                className="px-2.5 py-2 rounded-lg text-xs font-semibold text-center bg-teal-950/70 text-teal-300 border border-teal-500/30 hover:bg-teal-900/70 transition-colors flex items-center justify-center gap-1"
                            >
                                <ShieldCheck className="w-3 h-3 text-teal-400" />
                                Admin
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            {/* Bottom Profile / Auth Status */}
            <div className="p-4 border-t border-slate-800/80 bg-slate-950/80 backdrop-blur-md">
                {user ? (
                    <div className="space-y-3">
                        <div className="flex items-center gap-3 p-2.5 rounded-xl bg-slate-900/80 border border-slate-800">
                            <div className="w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center font-bold text-slate-950 text-sm shadow shrink-0">
                                {user.name.charAt(0).toUpperCase()}
                            </div>
                            <div className="flex-1 min-w-0">
                                <div className="text-xs font-semibold text-white truncate">
                                    {user.name}
                                </div>
                                <div className="text-[11px] text-slate-400 truncate">
                                    {user.email}
                                </div>
                                <div className="mt-0.5 inline-block">
                                    <span className="text-[9px] uppercase tracking-wider font-bold px-1.5 py-0.5 rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                        {user.role === 'admin' ? 'Administrator' : 'Siswa Aktif'}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <button
                            onClick={handleLogout}
                            className="w-full flex items-center justify-center gap-2 py-2 px-3 rounded-xl text-xs font-semibold text-rose-400 hover:text-rose-300 bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20 transition-colors"
                        >
                            <LogOut className="w-3.5 h-3.5" />
                            Keluar (Logout)
                        </button>
                    </div>
                ) : (
                    <div className="space-y-2">
                        <Link
                            href="/login"
                            onClick={() => setIsOpen?.(false)}
                            className="w-full flex items-center justify-center gap-2 py-2 px-3 rounded-xl text-xs font-semibold text-slate-200 bg-slate-900 hover:bg-slate-800 border border-slate-800 transition-colors"
                        >
                            <LogIn className="w-3.5 h-3.5 text-slate-400" />
                            Masuk ke Akun
                        </Link>
                        <Link
                            href="/register"
                            onClick={() => setIsOpen?.(false)}
                            className="w-full flex items-center justify-center gap-2 py-2 px-3 rounded-xl text-xs font-semibold text-slate-950 bg-emerald-400 hover:bg-emerald-300 shadow-md shadow-emerald-500/20 transition-all hover:scale-[1.01]"
                        >
                            <UserPlus className="w-3.5 h-3.5" />
                            Daftar Gratis
                        </Link>
                    </div>
                )}
            </div>

        </div>
    );

    return (
        <>
            {/* Desktop Fixed Sidebar */}
            <aside className="hidden lg:block w-64 shrink-0 h-screen sticky top-0 z-40">
                {sidebarContent}
            </aside>

            {/* Mobile Drawer (Slide-over) */}
            {isOpen && (
                <div className="fixed inset-0 z-50 lg:hidden flex">
                    {/* Backdrop */}
                    <div 
                        className="fixed inset-0 bg-black/70 backdrop-blur-sm transition-opacity animate-in fade-in duration-200"
                        onClick={() => setIsOpen(false)}
                    />
                    
                    {/* Drawer Panel */}
                    <div className="relative w-72 max-w-[85vw] h-full shadow-2xl animate-in slide-in-from-left duration-200 z-10">
                        {sidebarContent}
                    </div>
                </div>
            )}
        </>
    );
}
