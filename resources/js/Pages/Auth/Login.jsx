import React from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import { GraduationCap, LogIn, Sparkles, Shield, User, Lock, Mail } from 'lucide-react';

export default function Login() {
    const { data, setData, post, processing, errors } = useForm({
        email: '',
        password: '',
        remember: true,
    });

    const submit = (e) => {
        e.preventDefault();
        post('/login');
    };

    return (
        <div className="min-h-screen bg-slate-950 flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative overflow-hidden">
            <Head title="Masuk ke Akun" />

            {/* Background glowing ambient effects */}
            <div className="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none" />
            <div className="absolute bottom-10 right-1/4 w-80 h-80 bg-teal-500/10 rounded-full blur-3xl pointer-events-none" />

            <div className="sm:mx-auto sm:w-full sm:max-w-md relative z-10">
                <div className="flex justify-center">
                    <Link href="/" className="flex items-center gap-3 group">
                        <div className="w-12 h-12 rounded-2xl bg-gradient-to-tr from-emerald-600 via-emerald-500 to-teal-400 flex items-center justify-center shadow-xl shadow-emerald-500/20 group-hover:scale-105 transition-transform">
                            <GraduationCap className="w-7 h-7 text-slate-950 font-bold" />
                        </div>
                    </Link>
                </div>
                <h2 className="mt-4 text-center text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                    Masuk ke Saladdin LMS
                </h2>
                <p className="mt-2 text-center text-sm text-slate-400">
                    Lanjutkan pembelajaran Anda untuk meraih sertifikat
                </p>
            </div>

            <div className="mt-8 sm:mx-auto sm:w-full sm:max-w-md relative z-10 px-4 sm:px-0">
                <div className="bg-slate-900/90 border border-slate-800/90 backdrop-blur-xl py-8 px-6 shadow-2xl rounded-2xl sm:px-10">
                    <form className="space-y-5" onSubmit={submit}>
                        
                        {/* Email */}
                        <div>
                            <label className="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                                Alamat Email
                            </label>
                            <div className="relative">
                                <div className="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                    <Mail className="w-4 h-4" />
                                </div>
                                <input
                                    type="email"
                                    value={data.email}
                                    onChange={(e) => setData('email', e.target.value)}
                                    placeholder="nama@email.com"
                                    required
                                    className={`w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-950/80 border text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/40 text-sm transition-all ${
                                        errors.email ? 'border-rose-500' : 'border-slate-800 focus:border-emerald-500'
                                    }`}
                                />
                            </div>
                            {errors.email && (
                                <p className="mt-1 text-xs text-rose-400">{errors.email}</p>
                            )}
                        </div>

                        {/* Password */}
                        <div>
                            <div className="flex items-center justify-between mb-1.5">
                                <label className="block text-xs font-semibold uppercase tracking-wider text-slate-300">
                                    Kata Sandi
                                </label>
                            </div>
                            <div className="relative">
                                <div className="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                    <Lock className="w-4 h-4" />
                                </div>
                                <input
                                    type="password"
                                    value={data.password}
                                    onChange={(e) => setData('password', e.target.value)}
                                    placeholder="••••••••"
                                    required
                                    className={`w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-950/80 border text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/40 text-sm transition-all ${
                                        errors.password ? 'border-rose-500' : 'border-slate-800 focus:border-emerald-500'
                                    }`}
                                />
                            </div>
                            {errors.password && (
                                <p className="mt-1 text-xs text-rose-400">{errors.password}</p>
                            )}
                        </div>

                        {/* Remember Me */}
                        <div className="flex items-center justify-between">
                            <label className="flex items-center gap-2 cursor-pointer">
                                <input
                                    type="checkbox"
                                    checked={data.remember}
                                    onChange={(e) => setData('remember', e.target.checked)}
                                    className="w-4 h-4 rounded bg-slate-950 border-slate-700 text-emerald-500 focus:ring-emerald-500/40 focus:ring-offset-0"
                                />
                                <span className="text-xs text-slate-300 select-none">Ingat saya</span>
                            </label>
                        </div>

                        {/* Submit Button */}
                        <button
                            type="submit"
                            disabled={processing}
                            className="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-slate-950 font-bold text-sm shadow-lg shadow-emerald-500/25 transition-all flex items-center justify-center gap-2 hover:scale-[1.01] active:scale-[0.99] disabled:opacity-50"
                        >
                            <LogIn className="w-4 h-4" />
                            {processing ? 'Memproses...' : 'Masuk Sekarang'}
                        </button>
                    </form>

                    {/* Quick Demo Login Cards */}
                    <div className="mt-6 pt-6 border-t border-slate-800">
                        <div className="flex items-center gap-1.5 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">
                            <Sparkles className="w-3.5 h-3.5 text-amber-400" />
                            Akses Cepat Uji Coba (1-Click Demo)
                        </div>
                        <div className="grid grid-cols-2 gap-2">
                            <a
                                href="/demo-login/student"
                                className="p-2.5 rounded-xl bg-slate-950/70 border border-slate-800 hover:border-emerald-500/50 hover:bg-slate-950 text-left transition-all group"
                            >
                                <div className="flex items-center gap-1.5 text-emerald-400 text-xs font-semibold mb-0.5">
                                    <User className="w-3.5 h-3.5" /> Siswa Demo
                                </div>
                                <div className="text-[11px] text-slate-400 truncate">student@saladdin.com</div>
                            </a>

                            <a
                                href="/demo-login/admin"
                                className="p-2.5 rounded-xl bg-slate-950/70 border border-slate-800 hover:border-amber-500/50 hover:bg-slate-950 text-left transition-all group"
                            >
                                <div className="flex items-center gap-1.5 text-amber-400 text-xs font-semibold mb-0.5">
                                    <Shield className="w-3.5 h-3.5" /> Admin Demo
                                </div>
                                <div className="text-[11px] text-slate-400 truncate">admin@saladdin.com</div>
                            </a>
                        </div>
                    </div>

                    {/* Register Link */}
                    <p className="mt-6 text-center text-xs text-slate-400">
                        Belum punya akun?{' '}
                        <Link href="/register" className="font-semibold text-emerald-400 hover:text-emerald-300">
                            Daftar Sekarang Gratis
                        </Link>
                    </p>
                </div>
            </div>
        </div>
    );
}
