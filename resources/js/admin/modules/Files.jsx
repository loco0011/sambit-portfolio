import { useEffect, useRef, useState } from 'react';
import { api } from '../api';
import { ago, bytes, useTicker } from '../lib';
import { Led, ModLabel } from '../ui/Hardware';

// Shared by the Résumé module and the dashboard shortcut.
export function useResume() {
    const ticker = useTicker();
    const [info, setInfo] = useState(null);
    const [busy, setBusy] = useState(false);

    useEffect(() => {
        api('resume').then(setInfo).catch((e) => ticker(e.message, 'warn'));
    }, []);

    const upload = async (file) => {
        if (!file) return;
        if (file.type !== 'application/pdf') {
            ticker('Drive A only accepts PDF', 'warn');
            return;
        }
        const form = new FormData();
        form.append('file', file);
        setBusy(true);
        try {
            setInfo(await api('resume', { method: 'POST', body: form }));
            ticker(`Résumé written · ${bytes(file.size)} · live at /resume`);
        } catch (e) {
            ticker(e.message, 'warn');
        } finally {
            setBusy(false);
        }
    };

    return { info, busy, upload };
}

export default function Files() {
    const { info, busy, upload } = useResume();
    const [over, setOver] = useState(false);
    const input = useRef();

    return (
        <div className="dash">
            <section className="mod span-7">
                <ModLabel index="D1" aside={<><Led on={busy} color="orange" blink /> Drive A</>}>
                    Résumé · PDF
                </ModLabel>
                <div
                    className={`drive ${over ? 'drive--over' : ''} ${busy ? 'drive--busy' : ''}`}
                    role="button"
                    tabIndex={0}
                    onClick={() => input.current.click()}
                    onKeyDown={(e) => (e.key === 'Enter' || e.key === ' ') && input.current.click()}
                    onDragOver={(e) => {
                        e.preventDefault();
                        setOver(true);
                    }}
                    onDragLeave={() => setOver(false)}
                    onDrop={(e) => {
                        e.preventDefault();
                        setOver(false);
                        upload(e.dataTransfer.files[0]);
                    }}
                >
                    <div className="floppy" />
                    <div className="drive-mouth" />
                    <div className="drive-hint">{busy ? 'Writing to disk…' : over ? 'Release to insert' : 'Drop a PDF here · or click to browse'}</div>
                    <input ref={input} type="file" accept="application/pdf" hidden onChange={(e) => upload(e.target.files[0])} />
                </div>
            </section>

            <section className="mod span-5">
                <ModLabel index="D2">Disk status</ModLabel>
                <div className="lcd lcd-pad">
                    <div className="lcd-rows">
                        <div>
                            <span className="lcd-dim">File</span>
                            <span>RESUME.PDF</span>
                        </div>
                        <div>
                            <span className="lcd-dim">Status</span>
                            <span>{!info ? 'Reading…' : info.exists ? 'Ready' : 'Empty'}</span>
                        </div>
                        <div>
                            <span className="lcd-dim">Size</span>
                            <span>{info?.exists ? bytes(info.size) : '—'}</span>
                        </div>
                        <div>
                            <span className="lcd-dim">Written</span>
                            <span>{info?.exists ? ago(info.updated_at) : '—'}</span>
                        </div>
                        <div>
                            <span className="lcd-dim">Public URL</span>
                            <span>/resume</span>
                        </div>
                    </div>
                </div>
                <p className="note" style={{ marginTop: 22 }}>
                    Visitors download it as <b>Sambit_Maity_Full_Stack_Software_Engineer_Resume.pdf</b>. Uploading replaces the old file instantly.
                </p>
                <a className="btn btn--dark" href="/resume" target="_blank" rel="noreferrer" aria-disabled={!info?.exists}>
                    Test download ↗
                </a>
            </section>
        </div>
    );
}
