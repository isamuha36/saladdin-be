import React from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import { GraduationCap, UserPlus, User, Lock, Mail } from 'lucide-react';

export default function Register() {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    const submit = (e) => {
        e.preventDefault();
        post('/register');
    };

    return (
        <div className="min-h-screen bg-slate-950 flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative overflow-hidden">
            <Head title="Pendaftaran Akun Baru" />

            {/* Background glowing effects */}
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
                    Buat Akun Siswa Baru
                </h2>
                <p className="mt-2 text-center text-sm text-slate-400">
                    Bergabung bersama ribuan penuntut ilmu di Saladdin Marifi
                </p>
            </div>

            <div className="mt-8 sm:mx-auto sm:w-full sm:max-w-md relative z-10 px-4 sm:px-0">
                <div className="bg-slate-900/90 border border-slate-800/90 backdrop-blur-xl py-8 px-6 shadow-2xl rounded-2xl sm:px-10">
                    <form className="space-y-4" onSubmit={submit}>
                        
                        {/* Name */}
                        <div>
                            <label className="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                                Nama Lengkap
                            </label>
                            <div className="relative">
                                <div className="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                    <User className="w-4 h-4" />
                                </div>
                                <input
                                    type="text"
                                    value={data.name}
                                    onChange={(e) => setData('name', e.target.value)}
                                    placeholder="Contoh: Muhammad Ihsan"
                                    required
                                    className={`w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-950/80 border text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/40 text-sm transition-all ${
                                        errors.name ? 'border-rose-500' : 'border-slate-800 focus:border-emerald-500'
                                    }`}
                                />
                            </div>
                            {errors.name && (
                                <p className="mt-1 text-xs text-rose-400">{errors.name}</p>
                            )}
                        </div>

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
                            <label className="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                                Kata Sandi
                            </label>
                            <div className="relative">
                                <div className="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                    <Lock className="w-4 h-4" />
                                </div>
                                <input
                                    type="password"
                                    value={data.password}
                                    onChange={(e) => setData('password', e.target.value)}
                                    placeholder="Minimal 6 karakter"
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

                        {/* Password Confirmation */}
                        <div>
                            <label className="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                                Konfirmasi Kata Sandi
                            </label>
                            <div className="relative">
                                <div className="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                    <Lock className="w-4 h-4" />
                                </div>
                                <input
                                    type="password"
                                    value={data.password_confirmation}
                                    onChange={(e) => setData('password_confirmation', e.target.value)}
                                    placeholder="Ulangi kata sandi"
                                    required
                                    className="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-950/80 border border-slate-800 focus:border-emerald-500 text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/40 text-sm transition-all"
                                />
                            </div>
                        </div>

                        {/* Submit Button */}
                        <button
                            type="submit"
                            disabled={processing}
                            className="w-full mt-2 py-3 px-4 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-slate-950 font-bold text-sm shadow-lg shadow-emerald-500/25 transition-all flex items-center justify-center gap-2 hover:scale-[1.01] active:scale-[0.99] disabled:opacity-50"
                        >
                            <UserPlus className="w-4 h-4" />
                            {processing ? 'Mendaftarkan...' : 'Daftar Sekarang'}
                        </button>
                    </form>

                    {/* Login Link */}
                    <p className="mt-6 text-center text-xs text-slate-400">
                        Sudah memiliki akun?{' '}
                        <Link href="/login" className="font-semibold text-emerald-400 hover:text-emerald-300">
                            Masuk di Sini
                        </Link>
                    </p>
                </div>
            </div>
        </div>
    );
}
