import { useState, useEffect } from 'react';
import api from '../../lib/axios';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../../context/AuthContext';

export default function MobileLegends() {
    const navigate = useNavigate();
    const { user } = useAuth();
    const [matches, setMatches] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchMatches = async () => {
            try {
                const { data } = await api.get('/ml-match');
                setMatches(data.data || []);
            } catch (error) {
                console.error("Failed to fetch matches", error);
            } finally {
                setLoading(false);
            }
        };

        if (user && user.role !== 'admin' && user.role !== 'peserta_ml') {
            alert('Anda hanya bisa mengakses halaman Mobile Legends');
            navigate('/dashboard');
        } else {
            fetchMatches();
        }
    }, [user, navigate]);

    return (
        <div className="max-w-5xl mx-auto space-y-8">
            <div className="relative overflow-hidden bg-emerald-950 rounded-3xl p-8 sm:p-12 shadow-2xl border border-emerald-900/50">
                <div className="absolute top-0 right-0 w-[400px] h-[400px] bg-emerald-500/20 rounded-full blur-[80px]"></div>
                
                <div className="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div className="inline-flex items-center space-x-2 bg-emerald-500/20 px-3 py-1 rounded-full text-emerald-400 text-sm font-bold mb-4 border border-emerald-500/30">
                            <span className="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span>LIVE TOURNAMENT</span>
                        </div>
                        <h1 className="text-4xl font-black text-white mb-2">Mobile Legends</h1>
                        <p className="text-emerald-200">Official Tournament Brackets & Match Schedules</p>
                    </div>
                    <Link to="/dashboard" className="px-6 py-3 text-sm font-bold text-emerald-950 bg-emerald-400 rounded-full hover:bg-emerald-300 transition-colors shadow-lg shadow-emerald-500/20">
                        Back to Dashboard
                    </Link>
                </div>
            </div>

            <div className="bg-white p-8 rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 min-h-[400px]">
                <h2 className="text-2xl font-black text-slate-800 mb-8 border-b border-slate-100 pb-4">Round 1 Matches</h2>
                
                {loading ? (
                    <div className="flex flex-col justify-center items-center py-20 text-slate-400">
                        <div className="w-10 h-10 border-4 border-emerald-500 border-t-transparent rounded-full animate-spin mb-4"></div>
                        <p className="font-medium">Loading brackets...</p>
                    </div>
                ) : matches.length === 0 ? (
                    <div className="flex flex-col justify-center items-center py-20 bg-slate-50 border-2 border-dashed border-slate-200 rounded-3xl">
                        <svg className="w-16 h-16 text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                        <h3 className="text-lg font-bold text-slate-600">Bracket not generated yet</h3>
                        <p className="text-sm text-slate-400 mt-1">Please wait for the administrator to finalize the teams.</p>
                    </div>
                ) : (
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {matches.map(match => (
                            <div key={match.id} className="relative overflow-hidden border border-slate-200 rounded-2xl p-5 hover:border-emerald-300 transition-colors group bg-white shadow-sm hover:shadow-md">
                                <div className="absolute top-0 right-0 px-3 py-1 bg-slate-100 text-xs font-black text-slate-400 rounded-bl-lg">
                                    BO{match.best_of}
                                </div>
                                <div className="text-xs font-black text-slate-400 uppercase tracking-wider mb-4">Match #{match.id}</div>
                                
                                <div className="space-y-3">
                                    <div className={`flex justify-between items-center p-3 rounded-xl border ${match.winner_id === match.tim1_id ? 'bg-emerald-50 border-emerald-200 shadow-inner' : 'bg-slate-50 border-transparent'}`}>
                                        <span className={`font-bold ${match.winner_id === match.tim1_id ? 'text-emerald-700' : 'text-slate-700'}`}>
                                            {match.tim1?.nama || 'TBD'}
                                        </span>
                                        <span className={`font-black text-xl ${match.winner_id === match.tim1_id ? 'text-emerald-600' : 'text-slate-400'}`}>{match.tim1_score}</span>
                                    </div>
                                    
                                    <div className="flex items-center justify-center -my-1 relative z-10">
                                        <span className="bg-white px-2 text-xs font-black text-slate-300">VS</span>
                                    </div>

                                    <div className={`flex justify-between items-center p-3 rounded-xl border ${match.winner_id === match.tim2_id ? 'bg-emerald-50 border-emerald-200 shadow-inner' : 'bg-slate-50 border-transparent'}`}>
                                        <span className={`font-bold ${match.winner_id === match.tim2_id ? 'text-emerald-700' : 'text-slate-700'}`}>
                                            {match.tim2?.nama || 'BYE'}
                                        </span>
                                        <span className={`font-black text-xl ${match.winner_id === match.tim2_id ? 'text-emerald-600' : 'text-slate-400'}`}>{match.tim2_score}</span>
                                    </div>
                                </div>

                                <div className="mt-5 pt-4 border-t border-slate-100 text-center">
                                    <span className={`inline-flex items-center px-3 py-1 text-xs font-black rounded-full uppercase tracking-widest
                                        ${match.status === 'finished' ? 'bg-slate-100 text-slate-500' : 
                                          match.status === 'live' ? 'bg-red-50 text-red-600 border border-red-200' : 
                                          'bg-amber-50 text-amber-600 border border-amber-200'}`}>
                                        {match.status === 'live' && <span className="w-1.5 h-1.5 rounded-full bg-red-500 mr-2 animate-pulse"></span>}
                                        {match.status}
                                    </span>
                                </div>
                            </div>
                        ))}
                    </div>
                )}
            </div>
        </div>
    );
}
