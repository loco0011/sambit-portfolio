import { motion } from 'motion/react';
import { barcode, pad, stamp } from '../lib';

const STRIPS = 9;

function Paper({ message }) {
    return (
        <div className="receipt">
            <div className="receipt-center">
                <div className="receipt-title">*** TRANSMISSION ***</div>
                <div>NO. {pad(message.id)}</div>
                <div>{stamp(message.created_at)}</div>
            </div>
            <hr />
            <dl>
                <dt>FROM</dt>
                <dd>{message.name}</dd>
                <dt>EMAIL</dt>
                <dd>{message.email}</dd>
                <dt>ORG</dt>
                <dd>{message.company || '—'}</dd>
                <dt>ORIGIN</dt>
                <dd>{message.ip || '—'}</dd>
            </dl>
            <hr />
            <div className="receipt-body">{message.message}</div>
            <hr />
            <div className="receipt-center">
                {message.message.length} CHARS · {message.read_at ? 'READ' : 'NEW'}
            </div>
            <div className="barcode" style={{ '--bars': barcode(message.id) }} />
            <div className="receipt-center">THANK YOU FOR WRITING</div>
        </div>
    );
}

/**
 * The selected message, printed. Shredding cuts it into vertical strips that
 * fall away; onShredded fires once the last strip is gone.
 */
export default function Receipt({ message, shredding, onShredded }) {
    if (shredding) {
        return (
            <div className="receipt-strips">
                <div style={{ visibility: 'hidden' }}>
                    <Paper message={message} />
                </div>
                {Array.from({ length: STRIPS }, (_, i) => (
                    <motion.div
                        key={i}
                        className="receipt-strip"
                        style={{ clipPath: `inset(0 ${100 - ((i + 1) * 100) / STRIPS}% 0 ${(i * 100) / STRIPS}%)` }}
                        initial={{ y: 0, rotate: 0, opacity: 1 }}
                        animate={{ y: 520 + (i % 3) * 60, rotate: (i % 2 ? 1 : -1) * (2 + (i % 4)), opacity: 0 }}
                        transition={{ delay: 0.18 + i * 0.035, duration: 0.75, ease: [0.5, 0, 0.75, 0] }}
                        onAnimationComplete={i === STRIPS - 1 ? onShredded : undefined}
                    >
                        <Paper message={message} />
                    </motion.div>
                ))}
            </div>
        );
    }

    return (
        <motion.div
            key={message.id}
            initial={{ y: '-101%' }}
            animate={{ y: 0 }}
            transition={{ duration: 0.85, ease: [0.25, 0.8, 0.3, 1] }}
        >
            <Paper message={message} />
        </motion.div>
    );
}
