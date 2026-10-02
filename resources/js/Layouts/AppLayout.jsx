import React, { useState, useEffect } from 'react';
import { usePage } from '@inertiajs/react';
import Navbar from '../Components/Navbar';
import Footer from '../Components/Footer';
import { CheckCircle2, AlertCircle, Info, X } from 'lucide-react';

export default function AppLayout({ children, hideFooter = false, noContainer = false }) {
    const { flash } = usePage().props;
    const [toast, setToast] = useState(null);

    useEffect(() => {
        if (flash?.success) {
            setToast({ type: 'success', message: flash.success });
        } else if (flash?.error) {
            setToast({ type: 'error', message: flash.error });
        } else if (flash?.message) {
            setToast({ type: 'info', message: flash.message });
        }
    }, [flash]);

    useEffect(() => {
        if (toast) {
            const timer = setTimeout(() => {
                setToast(null);
            }, 6000);
            return () => clearTimeout(timer);
        }
    }, [toast]);

    return (
        <div className="min-h-screen bg-slate-950 text-slate-100 flex flex-col antialiased selection:bg-emerald-500 selection:text-white">
            <Navbar />

            {/* Floating Toast Notification */}
            {toast && (
                <div className="fixed top-20 right-4 sm:right-6 z-50 max-w-md w-full animate-in slide-in-from-top-4 fade-in duration-200">
                    <div className={`p-4 rounded-xl shadow-2xl border flex items-start gap-3 backdrop-blur-md ${
                        toast.type === 'success' 
                            ? 'bg-emerald-950/90 border-emerald-500/50 text-emerald-200' 
                            : toast.type === 'error'
                            ? 'bg-rose-950/90 border-rose-500/50 text-rose-200'
                            : 'bg-cyan-950/90 border-cyan-500/50 text-cyan-200'
                    }`}>
                        {toast.type === 'success' && <CheckCircle2 className="w-5 h-5 text-emerald-400 shrink-0 mt-0.5" />}
                        {toast.type === 'error' && <AlertCircle className="w-5 h-5 text-rose-400 shrink-0 mt-0.5" />}
                        {toast.type === 'info' && <Info className="w-5 h-5 text-cyan-400 shrink-0 mt-0.5" />}
                        
                        <div className="flex-1 text-sm font-medium leading-relaxed">
                            {toast.message}
                        </div>

                        <button 
                            onClick={() => setToast(null)}
                            className="p-1 rounded-md text-slate-400 hover:text-white hover:bg-slate-800/40 transition-colors"
                        >
                            <X className="w-4 h-4" />
                        </button>
                    </div>
                </div>
            )}

            <main className={`flex-1 ${noContainer ? '' : 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full'}`}>
                {children}
            </main>

            {!hideFooter && <Footer />}
        </div>
    );
}
