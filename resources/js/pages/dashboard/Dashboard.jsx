import { useAuth } from '../../context/AuthContext';
import { Link } from 'react-router-dom';

export default function Dashboard() {
    const { user } = useAuth();

    return (
        <div className="space-y-12 animate-fade-in-up">
            {/* Hero Header */}
            <div className="relative overflow-hidden bg-brand-black rounded-[40px] p-10 md:p-14 shadow-2xl border border-gray-800">
                <div className="absolute top-0 right-0 -mr-20 -mt-20 w-[400px] h-[400px] bg-brand-purple/20 rounded-full blur-[80px] animate-pulse"></div>
                <div className="absolute bottom-0 left-0 -ml-20 -mb-20 w-[300px] h-[300px] bg-brand-pink/20 rounded-full blur-[80px]"></div>
                
                <div className="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                    <div className="max-w-2xl">
                        <div className="inline-block mb-4 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 text-brand-pink font-semibold text-sm tracking-widest uppercase">
                            Innoventure Dashboard
                        </div>
                        <h1 className="text-4xl md:text-5xl font-black text-brand-white mb-4 leading-tight">
                            Welcome to the <br/>
                            <span className="text-transparent bg-clip-text bg-gradient-to-r from-brand-purple to-brand-pink">Innovation Hub</span>
                        </h1>
                        <p className="text-lg text-slate-400 leading-relaxed max-w-xl">
                            {user ? (
                                <>Hello, <span className="font-bold text-white">{user.name}</span>! You're ready to make an impact as <span className="uppercase text-brand-pink font-bold">{user.role}</span>.</>
                            ) : (
                                <>Join the largest tech competition this year. Login to submit your projects, or proceed directly to secure your Seminar Ticket.</>
                            )}
                        </p>
                    </div>
                    
                    {user && (
                        <div className="hidden lg:flex flex-col items-center justify-center p-6 bg-white/5 backdrop-blur-md border border-white/10 rounded-3xl min-w-[200px]">
                            <div className="w-20 h-20 bg-gradient-to-br from-brand-purple to-brand-pink rounded-full mb-4 p-1">
                                <div className="w-full h-full bg-brand-black rounded-full flex items-center justify-center text-2xl font-black text-white uppercase">
                                    {user.name.charAt(0)}
                                </div>
                            </div>
                            <h3 className="text-white font-bold text-lg">{user.name}</h3>
                            <p className="text-brand-pink text-sm font-semibold uppercase tracking-wider">{user.role}</p>
                        </div>
                    )}
                </div>
            </div>

            {/* Main Content Grid */}
            <div className="grid grid-cols-1 xl:grid-cols-3 gap-10">
                
                {/* Left Column (Competitions - Takes 2 cols on XL) */}
                <div className="xl:col-span-2 space-y-8">
                    <div className="flex items-center space-x-4 mb-2">
                        <div className="w-2 h-8 bg-brand-purple rounded-full"></div>
                        <h2 className="text-2xl font-black text-brand-black">Competition Submissions</h2>
                    </div>
                    
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {/* Web Dev Card */}
                        {(user?.role === 'peserta_webdev' || user?.role === 'admin') && (
                            <Link to="/competition/webdev" className="group relative overflow-hidden bg-white rounded-3xl p-8 shadow-xl shadow-brand-purple/5 border border-gray-100 hover:shadow-2xl hover:shadow-brand-purple/10 hover:-translate-y-1 transition-all duration-300 block">
                                <div className="absolute top-0 right-0 w-32 h-32 bg-brand-purple/5 rounded-bl-[100px] -z-0 transition-transform group-hover:scale-110"></div>
                                <div className="relative z-10">
                                    <div className="w-14 h-14 bg-brand-purple/10 text-brand-purple rounded-2xl flex items-center justify-center mb-6 transition-transform group-hover:scale-110">
                                        <svg className="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                    </div>
                                    <h3 className="text-xl font-bold text-brand-black mb-2 group-hover:text-brand-purple transition-colors">Web Development</h3>
                                    <p className="text-gray-500 text-sm leading-relaxed mb-6">Submit your project URL, GitHub repo, and pitch deck for review.</p>
                                    <span className="inline-flex items-center text-brand-purple font-bold text-sm">
                                        Submit Project <svg className="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" viewBox="0 0 20 20" fill="currentColor"><path fillRule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clipRule="evenodd" /></svg>
                                    </span>
                                </div>
                            </Link>
                        )}
                        
                        {/* UI/UX Card */}
                        {(user?.role === 'peserta_uiux' || user?.role === 'admin') && (
                            <Link to="/competition/uiux" className="group relative overflow-hidden bg-white rounded-3xl p-8 shadow-xl shadow-brand-pink/5 border border-gray-100 hover:shadow-2xl hover:shadow-brand-pink/10 hover:-translate-y-1 transition-all duration-300 block">
                                <div className="absolute top-0 right-0 w-32 h-32 bg-brand-pink/5 rounded-bl-[100px] -z-0 transition-transform group-hover:scale-110"></div>
                                <div className="relative z-10">
                                    <div className="w-14 h-14 bg-brand-pink/10 text-brand-pink rounded-2xl flex items-center justify-center mb-6 transition-transform group-hover:scale-110">
                                        <svg className="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    </div>
                                    <h3 className="text-xl font-bold text-brand-black mb-2 group-hover:text-brand-pink transition-colors">UI/UX Design</h3>
                                    <p className="text-gray-500 text-sm leading-relaxed mb-6">Submit your Figma link and design documentation for evaluation.</p>
                                    <span className="inline-flex items-center text-brand-pink font-bold text-sm">
                                        Submit Design <svg className="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" viewBox="0 0 20 20" fill="currentColor"><path fillRule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clipRule="evenodd" /></svg>
                                    </span>
                                </div>
                            </Link>
                        )}
                        
                        {/* Mobile Legends Card (Full width on medium) */}
                        {(user?.role === 'peserta_ml' || user?.role === 'admin') && (
                            <Link to="/competition/ml" className="group md:col-span-2 relative overflow-hidden bg-brand-black rounded-3xl p-8 shadow-xl shadow-brand-purple/10 border border-brand-purple/30 hover:-translate-y-1 transition-all duration-300 block">
                                <div className="absolute top-0 right-0 w-48 h-48 bg-brand-purple/10 rounded-bl-[150px] -z-0 transition-transform group-hover:scale-110"></div>
                                <div className="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between">
                                    <div>
                                        <div className="w-12 h-12 bg-white/10 text-white rounded-2xl flex items-center justify-center mb-4">
                                            <svg className="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                        </div>
                                        <h3 className="text-2xl font-bold text-white mb-2 group-hover:text-brand-pink transition-colors">Mobile Legends</h3>
                                        <p className="text-gray-400 text-sm leading-relaxed max-w-sm mb-6 sm:mb-0">Check your team's bracket, live tournament schedules, and latest match results.</p>
                                    </div>
                                    <span className="inline-flex items-center justify-center px-6 py-3 bg-brand-purple text-white rounded-full font-bold text-sm shadow-lg shadow-brand-purple/30 group-hover:bg-brand-pink group-hover:text-brand-black transition-colors">
                                        View Brackets
                                    </span>
                                </div>
                            </Link>
                        )}
                    </div>
                </div>

                {/* Right Column (Activities) */}
                <div className="space-y-8">
                    <div className="flex items-center space-x-4 mb-2">
                        <div className="w-2 h-8 bg-brand-pink rounded-full"></div>
                        <h2 className="text-2xl font-black text-brand-black">Explore</h2>
                    </div>

                    <div className="flex flex-col gap-6">
                        {/* Leaderboard Card */}
                        <Link to="/leaderboard" className="group relative overflow-hidden bg-gradient-to-br from-brand-purple to-brand-pink rounded-3xl p-8 shadow-xl shadow-brand-purple/30 border border-transparent hover:-translate-y-1 transition-all duration-300 block">
                            <div className="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-bl-[100px] -z-0 transition-transform group-hover:scale-110"></div>
                            <div className="relative z-10">
                                <div className="w-14 h-14 bg-white/20 text-white rounded-2xl flex items-center justify-center mb-6 backdrop-blur-sm">
                                    <svg className="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                                </div>
                                <h3 className="text-xl font-bold text-white mb-2">Live Leaderboard</h3>
                                <p className="text-white/80 text-sm leading-relaxed mb-6">Track live assessment scores and find out who's currently leading the competition.</p>
                                <span className="inline-flex items-center text-white font-bold text-sm bg-white/20 px-4 py-2 rounded-full">
                                    See Rankings <svg className="w-4 h-4 ml-1" viewBox="0 0 20 20" fill="currentColor"><path fillRule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clipRule="evenodd" /></svg>
                                </span>
                            </div>
                        </Link>

                        {/* Seminar Ticket Card */}
                        <Link to="/seminar/register" className="group relative overflow-hidden bg-white rounded-3xl p-8 shadow-xl shadow-gray-200/50 border border-gray-100 hover:shadow-2xl hover:shadow-brand-purple/10 hover:-translate-y-1 transition-all duration-300 block">
                            <div className="absolute top-0 right-0 w-24 h-24 bg-gray-50 rounded-bl-[80px] -z-0 transition-transform group-hover:scale-110"></div>
                            <div className="relative z-10 flex items-center space-x-6">
                                <div className="w-16 h-16 bg-gray-50 text-gray-400 rounded-2xl flex items-center justify-center shrink-0 border border-gray-100 group-hover:text-brand-purple group-hover:border-brand-purple/30 transition-colors">
                                    <svg className="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" /></svg>
                                </div>
                                <div>
                                    <h3 className="text-lg font-bold text-brand-black mb-1 group-hover:text-brand-purple transition-colors">Seminar Ticket</h3>
                                    <p className="text-gray-500 text-sm leading-relaxed">Claim your spot for the Grand Tech Seminar.</p>
                                </div>
                            </div>
                        </Link>
                    </div>
                </div>

            </div>
        </div>
    );
}
