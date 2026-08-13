import { useEffect, useMemo, useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../../context/AuthContext';
import api from '../../lib/axios';

const CARD_WIDTH = 240;
const CARD_HEIGHT = 112;
const SLOT_HEIGHT = 148;
const CONNECTOR_WIDTH = 88;
const ROUND_HEADER_HEIGHT = 56;
const CONNECTOR_JOIN = 36;
const COLORS = {
    background: '#020617',
    backgroundAlt: '#07111F',
    card: '#0F172A',
    cardHeader: '#020617',
    border: '#334155',
    primary: '#F8FAFC',
    secondary: '#94A3B8',
    emerald: '#34D399',
    amber: '#FBBF24',
    red: '#F87171',
};

function roundTitle(index, count) {
    if (index === count - 1) return 'Grand Final';
    if (count === 2 && index === 0) return 'Round 1';
    if (index === count - 2) return 'Semi Final';
    if (index === count - 3 && count >= 4) return 'Quarter Final';
    return `Round ${index + 1}`;
}

function teamName(match, side) {
    const team = side === 1 ? match.tim1 : match.tim2;
    const teamId = side === 1 ? match.tim1_id : match.tim2_id;
    if (team?.nama) return team.nama;
    if (side === 2 && teamId === null && match.round === 1) return 'BYE';
    return 'TBD';
}

function getWinnerTeam(match) {
    if (!match?.winner_id) return null;
    if (match.tim1_id === match.winner_id) return match.tim1;
    if (match.tim2_id === match.winner_id) return match.tim2;
    return match.winner || null;
}

function placeholderMatch(round, position, sourceMatches = []) {
    const tim1 = getWinnerTeam(sourceMatches[position * 2]);
    const tim2 = getWinnerTeam(sourceMatches[(position * 2) + 1]);

    return {
        id: `placeholder-${round}-${position}`,
        round,
        tim1_id: tim1?.id || null,
        tim2_id: tim2?.id || null,
        tim1_score: null,
        tim2_score: null,
        winner_id: null,
        best_of: round > 1 ? 5 : 3,
        status: 'upcoming',
        tim1,
        tim2,
        winner: null,
        placeholder: true,
    };
}

function StatusBadge({ status }) {
    const styles = {
        live: 'border-red-400/40 bg-red-400/10 text-red-300',
        finished: 'border-emerald-400/40 bg-emerald-400/10 text-emerald-300',
        upcoming: 'border-amber-400/40 bg-amber-400/10 text-amber-300',
    };
    const inlineStyles = {
        live: { color: '#FCA5A5', borderColor: 'rgba(248, 113, 113, .45)', backgroundColor: 'rgba(248, 113, 113, .12)' },
        finished: { color: '#6EE7B7', borderColor: 'rgba(52, 211, 153, .45)', backgroundColor: 'rgba(52, 211, 153, .12)' },
        upcoming: { color: '#FCD34D', borderColor: 'rgba(251, 191, 36, .45)', backgroundColor: 'rgba(251, 191, 36, .12)' },
    };

    return (
        <span
            className={`inline-flex items-center rounded-full border px-2.5 py-1 text-[10px] font-black uppercase tracking-[0.12em] ${styles[status] || styles.upcoming}`}
            style={inlineStyles[status] || inlineStyles.upcoming}
        >
            {status === 'live' && <span className="mr-1.5 h-1.5 w-1.5 animate-pulse rounded-full bg-red-400" />}
            {status || 'upcoming'}
        </span>
    );
}

function TeamRow({ match, side }) {
    const teamId = side === 1 ? match.tim1_id : match.tim2_id;
    const score = side === 1 ? match.tim1_score : match.tim2_score;
    const winner = Boolean(teamId && match.winner_id === teamId);
    const finished = match.status === 'finished';
    const rowStyle = winner
        ? { color: '#6EE7B7', borderColor: 'rgba(52, 211, 153, .3)', backgroundColor: 'rgba(52, 211, 153, .12)' }
        : { color: finished ? COLORS.secondary : '#E2E8F0', borderColor: COLORS.border, backgroundColor: COLORS.card };

    return (
        <div className={`flex h-[35px] items-center justify-between border-t px-3 first:border-t-0 ${winner ? 'border-emerald-400/30 bg-emerald-400/10 text-emerald-300' : finished ? 'border-slate-700 bg-slate-900 text-slate-400' : 'border-slate-700 bg-slate-900 text-slate-200'}`} style={rowStyle}>
            <span className={`min-w-0 flex-1 truncate whitespace-nowrap pr-3 text-xs ${winner ? 'font-black' : 'font-semibold'}`}>{teamName(match, side)}</span>
            <span className={`shrink-0 text-base font-black ${winner ? 'text-emerald-300' : finished ? 'text-slate-400' : 'text-slate-300'}`} style={{ color: winner ? '#6EE7B7' : finished ? COLORS.secondary : '#CBD5E1' }}>{score ?? '-'}</span>
        </div>
    );
}

function MatchCard({ match, roundIndex, matchIndex, hasNextRound, isGrandFinal }) {
    const spacing = SLOT_HEIGHT * (2 ** roundIndex);
    const center = spacing * (matchIndex + 0.5);
    const nextCenter = SLOT_HEIGHT * (2 ** (roundIndex + 1)) * (Math.floor(matchIndex / 2) + 0.5);
    const delta = nextCenter - center;
    const cardBorder = isGrandFinal ? 'rgba(251, 191, 36, .55)' : match.status === 'live' ? 'rgba(248, 113, 113, .55)' : COLORS.border;
    const connectorColor = match.winner_id ? 'rgba(52, 211, 153, .65)' : '#475569';

    return (
        <div className="absolute left-0" style={{ top: center - (CARD_HEIGHT / 2), width: CARD_WIDTH, height: CARD_HEIGHT }}>
            <div
                className={`relative z-10 h-full overflow-hidden rounded-xl border bg-slate-900/90 shadow-lg shadow-black/30 backdrop-blur transition-colors hover:bg-slate-900 ${isGrandFinal ? 'border-amber-400/50 shadow-amber-500/10' : match.status === 'live' ? 'border-red-400/50 hover:border-red-400' : 'border-slate-700 hover:border-slate-500'}`}
                style={{ backgroundColor: COLORS.card, borderColor: cardBorder, boxShadow: isGrandFinal ? '0 12px 30px rgba(251, 191, 36, .10)' : '0 12px 28px rgba(0, 0, 0, .30)' }}
            >
                <div
                    className={`flex h-[40px] items-center justify-between border-b px-3 ${isGrandFinal ? 'border-amber-400/30 bg-amber-400/10' : 'border-slate-700 bg-slate-950/80'}`}
                    style={{ backgroundColor: isGrandFinal ? 'rgba(251, 191, 36, .12)' : 'rgba(2, 6, 23, .88)', borderColor: isGrandFinal ? 'rgba(251, 191, 36, .35)' : COLORS.border }}
                >
                    <span className={`text-[10px] font-black uppercase tracking-[0.14em] ${isGrandFinal ? 'text-amber-300' : 'text-slate-200'}`} style={{ color: isGrandFinal ? '#FCD34D' : '#E2E8F0' }}>
                        {match.placeholder ? 'TBD' : `M${match.id}`} <span className="text-slate-500">•</span> BO{match.best_of}
                    </span>
                    <StatusBadge status={match.status} />
                </div>
                <TeamRow match={match} side={1} />
                <TeamRow match={match} side={2} />
            </div>

            {hasNextRound && (
                <>
                    <span className="pointer-events-none absolute border-t-2" style={{ left: CARD_WIDTH, top: CARD_HEIGHT / 2, width: CONNECTOR_JOIN, borderColor: connectorColor }} />
                    <span
                        className="pointer-events-none absolute border-r-2"
                        style={{
                            left: CARD_WIDTH + CONNECTOR_JOIN - 2,
                            top: delta >= 0 ? CARD_HEIGHT / 2 : (CARD_HEIGHT / 2) + delta,
                            height: Math.abs(delta),
                            borderColor: connectorColor,
                        }}
                    />
                    <span className="pointer-events-none absolute border-t-2" style={{ left: CARD_WIDTH + CONNECTOR_JOIN, top: (CARD_HEIGHT / 2) + delta, width: CONNECTOR_WIDTH - CONNECTOR_JOIN, borderColor: connectorColor }} />
                </>
            )}
            {isGrandFinal && (
                <span className="pointer-events-none absolute border-t-2" style={{ left: CARD_WIDTH, top: CARD_HEIGHT / 2, width: CONNECTOR_WIDTH, borderColor: match.winner_id ? 'rgba(52, 211, 153, .65)' : 'rgba(251, 191, 36, .65)' }} />
            )}
        </div>
    );
}

function BracketRound({ matches, roundIndex, roundCount, bracketHeight }) {
    const title = roundTitle(roundIndex, roundCount);
    const teamCount = Math.max(2, matches.length * 2);

    return (
        <section className="relative shrink-0" style={{ width: CARD_WIDTH + CONNECTOR_WIDTH, height: bracketHeight }}>
            <div className={`absolute left-0 top-0 flex w-60 flex-col items-center justify-center border-b text-center ${roundIndex === roundCount - 1 ? 'border-amber-400/50' : 'border-emerald-500/40'}`} style={{ height: ROUND_HEADER_HEIGHT, borderColor: roundIndex === roundCount - 1 ? 'rgba(251, 191, 36, .5)' : 'rgba(52, 211, 153, .4)' }}>
                <span
                    className={`text-[10px] font-black uppercase tracking-[0.16em] ${roundIndex === roundCount - 1 ? 'text-amber-400' : 'text-emerald-400'}`}
                    style={{ color: roundIndex === roundCount - 1 ? COLORS.amber : COLORS.emerald }}
                >
                    {title}
                </span>
                <span className="mt-1 text-sm font-black uppercase text-white" style={{ color: COLORS.primary }}>{teamCount} Teams</span>
            </div>
            <div className="absolute left-0" style={{ top: ROUND_HEADER_HEIGHT, height: bracketHeight - ROUND_HEADER_HEIGHT }}>
                {matches.map((match, index) => (
                    <MatchCard
                        key={match.id}
                        match={match}
                        roundIndex={roundIndex}
                        matchIndex={index}
                        hasNextRound={roundIndex < roundCount - 1}
                        isGrandFinal={roundIndex === roundCount - 1}
                    />
                ))}
            </div>
        </section>
    );
}

function ChampionCard({ finalMatch, bracketHeight }) {
    const hasChampion = finalMatch?.status === 'finished' && Boolean(finalMatch?.winner_id);
    const champion = hasChampion && (finalMatch?.winner?.nama
        || (finalMatch?.winner_id === finalMatch?.tim1_id ? finalMatch?.tim1?.nama : finalMatch?.tim2?.nama)
    ) || 'TBD';

    return (
        <section className="relative z-10 shrink-0" style={{ width: 280, minWidth: 280, height: bracketHeight }}>
            <div className="absolute left-0 top-0 flex w-full items-center justify-center border-b border-amber-400/40" style={{ height: ROUND_HEADER_HEIGHT, borderColor: 'rgba(251, 191, 36, .4)' }}>
                <span className="text-[10px] font-black uppercase tracking-[0.16em] text-amber-400" style={{ color: COLORS.amber }}>Champion</span>
            </div>
            <div className="absolute left-0 flex w-full -translate-y-1/2 items-center pl-10" style={{ top: ROUND_HEADER_HEIGHT + ((bracketHeight - ROUND_HEADER_HEIGHT) / 2) }}>
                <div className="pointer-events-none absolute left-0 top-1/2 w-10 border-t-2" style={{ borderColor: hasChampion ? 'rgba(52, 211, 153, .65)' : 'rgba(251, 191, 36, .65)' }} />
                <div
                    className="relative z-10 min-w-[220px] overflow-hidden rounded-2xl border border-amber-400/50 bg-gradient-to-b from-amber-400/15 via-slate-900 to-slate-950 p-7 text-center shadow-xl shadow-amber-500/10"
                    style={{ width: 240, minWidth: 240, color: COLORS.primary, borderColor: 'rgba(251, 191, 36, .55)', background: 'linear-gradient(180deg, rgba(251,191,36,.16) 0%, #0F172A 46%, #020617 100%)', boxShadow: '0 18px 40px rgba(251,191,36,.10)' }}
                >
                    <div className="mb-3 text-4xl" aria-hidden="true">🏆</div>
                    <p className="text-[10px] font-black uppercase tracking-[0.2em] text-amber-400" style={{ color: COLORS.amber }}>Tournament Champion</p>
                    <p className="mt-4 truncate whitespace-nowrap text-2xl font-black uppercase text-white" style={{ color: COLORS.primary }} title={champion}>{champion}</p>
                    <p className="mt-3 text-[10px] font-bold uppercase tracking-[0.22em] text-slate-500" style={{ color: '#64748B' }}>Single Elimination</p>
                </div>
            </div>
        </section>
    );
}

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
                console.error('Failed to fetch matches', error);
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

    const { rounds, finalMatch } = useMemo(() => {
        const grouped = matches.reduce((result, match) => {
            const round = Number(match.round) || 1;
            result[round] ||= [];
            result[round].push(match);
            return result;
        }, {});

        const apiRounds = Object.entries(grouped)
            .sort(([roundA], [roundB]) => Number(roundA) - Number(roundB))
            .map(([round, roundMatches]) => ({
                round: Number(round),
                matches: [...roundMatches].sort((a, b) => a.id - b.id),
            }));
        if (apiRounds.length === 0) return { rounds: [], finalMatch: null };

        const firstRoundMatchCount = apiRounds[0].matches.length;
        const expectedRoundCount = Math.max(1, Math.ceil(Math.log2(firstRoundMatchCount * 2)));
        const displayRounds = apiRounds.map((round) => ({ ...round, matches: [...round.matches] }));

        // Older brackets may contain the next match before its participant
        // columns were populated. Resolve them visually from feeder winners.
        for (let index = 1; index < displayRounds.length; index += 1) {
            const sourceMatches = displayRounds[index - 1].matches;
            displayRounds[index].matches = displayRounds[index].matches.map((match, position) => {
                const firstWinner = getWinnerTeam(sourceMatches[position * 2]);
                const secondWinner = getWinnerTeam(sourceMatches[(position * 2) + 1]);

                return {
                    ...match,
                    tim1: match.tim1 || firstWinner,
                    tim2: match.tim2 || secondWinner,
                    tim1_id: match.tim1_id || firstWinner?.id || null,
                    tim2_id: match.tim2_id || secondWinner?.id || null,
                };
            });
        }

        for (let round = apiRounds.at(-1).round + 1; round <= expectedRoundCount; round += 1) {
            const matchCount = Math.max(1, Math.ceil(firstRoundMatchCount / (2 ** (round - 1))));
            const sourceMatches = displayRounds.at(-1).matches;
            displayRounds.push({
                round,
                matches: Array.from({ length: matchCount }, (_, index) => placeholderMatch(round, index, sourceMatches)),
            });
        }

        const highestApiRound = apiRounds.at(-1);
        const validFinal = highestApiRound.round === expectedRoundCount && highestApiRound.matches.length === 1
            ? highestApiRound.matches[0]
            : null;

        return { rounds: displayRounds, finalMatch: validFinal };
    }, [matches]);

    const firstRoundSize = rounds[0]?.matches.length || 1;
    const bracketHeight = Math.max(352, ROUND_HEADER_HEIGHT + (firstRoundSize * SLOT_HEIGHT));

    return (
        <div className="mx-auto max-w-7xl space-y-8">
            <div className="relative overflow-hidden rounded-3xl border border-emerald-900/50 bg-emerald-950 p-8 shadow-2xl sm:p-12">
                <div className="absolute right-0 top-0 h-[400px] w-[400px] rounded-full bg-emerald-500/20 blur-[80px]" />
                <div className="relative z-10 flex flex-col items-start justify-between gap-6 md:flex-row md:items-center">
                    <div>
                        <div className="mb-4 inline-flex items-center space-x-2 rounded-full border border-emerald-500/30 bg-emerald-500/20 px-3 py-1 text-sm font-bold text-emerald-400">
                            <span className="h-2 w-2 animate-pulse rounded-full bg-emerald-400" />
                            <span>LIVE TOURNAMENT</span>
                        </div>
                        <h1 className="mb-2 text-4xl font-black text-white">Mobile Legends</h1>
                        <p className="text-emerald-200">Official Tournament Brackets & Match Schedules</p>
                    </div>
                    <Link to="/dashboard" className="rounded-full bg-emerald-400 px-6 py-3 text-sm font-bold text-emerald-950 shadow-lg shadow-emerald-500/20 transition-colors hover:bg-emerald-300">
                        Back to Dashboard
                    </Link>
                </div>
            </div>

            <div
                className="relative overflow-hidden rounded-3xl border border-slate-800 bg-gradient-to-br from-slate-950 via-[#07111F] to-emerald-950 shadow-2xl"
                style={{ color: COLORS.primary, borderColor: '#1E293B', background: 'linear-gradient(135deg, #020617 0%, #07111F 58%, #022C22 100%)', boxShadow: '0 24px 60px rgba(2, 6, 23, .38)' }}
            >
                <div className="pointer-events-none absolute -left-32 -top-32 h-80 w-80 rounded-full bg-emerald-500/10 blur-3xl" />
                <div className="pointer-events-none absolute -bottom-32 right-0 h-96 w-96 rounded-full bg-cyan-500/10 blur-3xl" />
                <div className="relative border-b border-slate-800 bg-slate-950/40 px-7 py-7" style={{ borderColor: '#1E293B', backgroundColor: 'rgba(2, 6, 23, .52)' }}>
                    <p className="text-[10px] font-black uppercase tracking-[0.22em] text-emerald-400" style={{ color: COLORS.emerald }}>Single Elimination</p>
                    <h2 className="mt-2 text-3xl font-black tracking-tight text-white" style={{ color: COLORS.primary }}>Knockout Bracket</h2>
                    <p className="mt-2 text-sm font-medium text-slate-400" style={{ color: COLORS.secondary }}>Tournament Championship</p>
                </div>

                {loading ? (
                    <div className="relative flex flex-col items-center justify-center py-24 text-slate-400" style={{ color: COLORS.secondary }}>
                        <div className="mb-4 h-10 w-10 animate-spin rounded-full border-4 border-emerald-500 border-t-transparent" />
                        <p className="font-medium">Loading tournament bracket...</p>
                    </div>
                ) : matches.length === 0 ? (
                    <div className="relative m-8 flex flex-col items-center justify-center rounded-3xl border-2 border-dashed border-slate-700 bg-slate-900/60 py-20 text-center" style={{ borderColor: COLORS.border, backgroundColor: 'rgba(15, 23, 42, .68)' }}>
                        <h3 className="text-lg font-bold text-slate-200" style={{ color: '#E2E8F0' }}>Bracket not generated yet</h3>
                        <p className="mt-1 text-sm text-slate-400" style={{ color: COLORS.secondary }}>Please wait for the administrator to generate the tournament bracket.</p>
                    </div>
                ) : (
                    <div className="relative overflow-x-auto p-6 sm:p-8" style={{ backgroundColor: 'rgba(2, 6, 23, .18)' }}>
                        <div className="flex min-w-max items-stretch" style={{ height: bracketHeight, minWidth: (rounds.length * (CARD_WIDTH + CONNECTOR_WIDTH)) + 280 }}>
                            {rounds.map(({ round, matches: roundMatches }, index) => (
                                <BracketRound
                                    key={round}
                                    matches={roundMatches}
                                    roundIndex={index}
                                    roundCount={rounds.length}
                                    bracketHeight={bracketHeight}
                                />
                            ))}
                            <ChampionCard finalMatch={finalMatch} bracketHeight={bracketHeight} />
                        </div>
                    </div>
                )}
            </div>
        </div>
    );
}
