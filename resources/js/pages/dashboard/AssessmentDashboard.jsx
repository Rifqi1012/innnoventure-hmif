import { useState, useEffect } from 'react';
import api from '../../lib/axios';

export default function AssessmentDashboard() {
    const [activeTab, setActiveTab] = useState('webdev');
    const [webdevData, setWebdevData] = useState([]);
    const [uiuxData, setUiuxData] = useState([]);
    const [scores, setScores] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchData = async () => {
            try {
                setLoading(true);
                // Fetch progress
                const [webdevRes, uiuxRes, scoresRes] = await Promise.all([
                    api.get('/webdev-progress'),
                    api.get('/ui-progress'),
                    api.get('/penilaian')
                ]);
                
                setWebdevData(webdevRes.data.data || []);
                setUiuxData(uiuxRes.data.data || []);
                setScores(scoresRes.data.payload || []);
            } catch (error) {
                console.error("Error fetching assessment data", error);
            } finally {
                setLoading(false);
            }
        };
        fetchData();
    }, []);

    // Helper to compute average score for a webdev progress
    const getAverageScore = (progressId) => {
        const relevantScores = scores.filter(s => s.webdev_progress_id === progressId);
        if (relevantScores.length === 0) return null;
        
        const total = relevantScores.reduce((sum, item) => sum + item.skor, 0);
        return (total / relevantScores.length).toFixed(1);
    };

    return (
        <div className="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 mt-20">
            <div className="space-y-8">
                <div className="relative overflow-hidden bg-brand-black rounded-3xl p-10 shadow-2xl border border-gray-800">
                <div className="absolute top-0 right-0 -mr-20 -mt-20 w-[400px] h-[400px] bg-brand-purple/20 rounded-full blur-[80px]"></div>
                
                <div className="relative z-10">
                    <h1 className="text-4xl font-black text-brand-white mb-4">
                        Assessment Dashboard
                    </h1>
                    <p className="text-lg text-slate-300 leading-relaxed max-w-2xl">
                        Live leaderboard and assessment results for Web Development and UI/UX Design competitions.
                    </p>
                </div>
            </div>

            {/* Tabs */}
            <div className="flex space-x-4 mb-8 bg-white p-2 rounded-2xl shadow-sm border border-gray-100 max-w-md mx-auto sm:mx-0">
                <button
                    onClick={() => setActiveTab('webdev')}
                    className={`flex-1 py-3 px-6 rounded-xl font-bold text-sm transition-all duration-300 ${
                        activeTab === 'webdev'
                            ? 'bg-brand-purple text-white shadow-lg shadow-brand-purple/30'
                            : 'text-gray-500 hover:bg-gray-50'
                    }`}
                >
                    Web Development
                </button>
                <button
                    onClick={() => setActiveTab('uiux')}
                    className={`flex-1 py-3 px-6 rounded-xl font-bold text-sm transition-all duration-300 ${
                        activeTab === 'uiux'
                            ? 'bg-brand-pink text-brand-black shadow-lg shadow-brand-pink/30'
                            : 'text-gray-500 hover:bg-gray-50'
                    }`}
                >
                    UI/UX Design
                </button>
            </div>

            {loading ? (
                <div className="flex justify-center items-center py-20">
                    <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-brand-purple"></div>
                </div>
            ) : (
                <div className="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                    <div className="overflow-x-auto">
                        <table className="w-full text-left border-collapse">
                            <thead>
                                <tr className="bg-gray-50 border-b border-gray-100">
                                    <th className="py-5 px-6 font-bold text-gray-900">Rank</th>
                                    <th className="py-5 px-6 font-bold text-gray-900">Team Name</th>
                                    <th className="py-5 px-6 font-bold text-gray-900">Project Title</th>
                                    <th className="py-5 px-6 font-bold text-gray-900 text-center">Avg. Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                {activeTab === 'webdev' ? (
                                    webdevData.length > 0 ? (
                                        [...webdevData].sort((a, b) => (getAverageScore(b.id) || 0) - (getAverageScore(a.id) || 0)).map((item, index) => {
                                            const score = getAverageScore(item.id);
                                            return (
                                                <tr key={item.id} className="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                                                    <td className="py-4 px-6">
                                                        <span className={`inline-flex items-center justify-center w-8 h-8 rounded-full font-bold ${
                                                            index === 0 ? 'bg-yellow-100 text-yellow-700' : 
                                                            index === 1 ? 'bg-gray-200 text-gray-700' :
                                                            index === 2 ? 'bg-orange-100 text-orange-700' : 'bg-gray-50 text-gray-500'
                                                        }`}>
                                                            {index + 1}
                                                        </span>
                                                    </td>
                                                    <td className="py-4 px-6 font-semibold text-brand-black">{item.tim?.nama || 'Unknown Team'}</td>
                                                    <td className="py-4 px-6 text-gray-600">{item.judul_proyek}</td>
                                                    <td className="py-4 px-6 text-center">
                                                        <span className={`px-4 py-1.5 rounded-full text-sm font-bold ${
                                                            score ? 'bg-brand-purple/10 text-brand-purple' : 'bg-gray-100 text-gray-500'
                                                        }`}>
                                                            {score ? score : 'Unscored'}
                                                        </span>
                                                    </td>
                                                </tr>
                                            );
                                        })
                                    ) : (
                                        <tr>
                                            <td colSpan="4" className="py-12 text-center text-gray-500">No Web Development projects submitted yet.</td>
                                        </tr>
                                    )
                                ) : (
                                    uiuxData.length > 0 ? (
                                        uiuxData.map((item, index) => (
                                            <tr key={item.id} className="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                                                <td className="py-4 px-6">
                                                    <span className="inline-flex items-center justify-center w-8 h-8 rounded-full font-bold bg-gray-50 text-gray-500">
                                                        {index + 1}
                                                    </span>
                                                </td>
                                                <td className="py-4 px-6 font-semibold text-brand-black">{item.tim?.nama || 'Unknown Team'}</td>
                                                <td className="py-4 px-6 text-gray-600">{item.judul_proyek || 'N/A'}</td>
                                                <td className="py-4 px-6 text-center">
                                                    <span className="px-4 py-1.5 rounded-full text-sm font-bold bg-gray-100 text-gray-500">
                                                        Pending Scoring
                                                    </span>
                                                </td>
                                            </tr>
                                        ))
                                    ) : (
                                        <tr>
                                            <td colSpan="4" className="py-12 text-center text-gray-500">No UI/UX projects submitted yet.</td>
                                        </tr>
                                    )
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>
            )}
            </div>
        </div>
    );
}
