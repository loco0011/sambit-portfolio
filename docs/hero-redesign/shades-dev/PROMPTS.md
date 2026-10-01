# Shades Dev: image prompts

Generate each pose as its own image in ChatGPT. Upload the original 6-panel reference image with the first
prompt so the character stays the same, then keep going in the same chat for the other poses.

## Paste first (once)

> I'm attaching an illustration of a character in 6 poses. Recreate this exact character, one pose per image,
> in the same illustration style, skin tone, soft shading and lighting. Changes for every image:
> - He now wears **black over-ear headphones**: a matte black band resting on the top of his head and cushioned
>   ear cups covering both ears, each cup with a tiny glowing lime-green (#d4ff4f) light. Keep the headphones
>   the same in every pose.
> - **Hands must be anatomically correct**: five clearly separated fingers, natural thumb, no merged or melted
>   fingers.
> - Square image, **1024 × 1024**, the character centred with some space around him.
> - Background: **plain flat #07070a** (near black), no panel border, no frame, no vignette, no glow.
>
> Keep everything else from the reference: bald head, black sunglasses with lime-green glints, black hoodie,
> the same props. Start with pose 1. Only one image per reply.

## Then one per message

1. **Code mode on**: sitting at a dark laptop with a `</>` logo, holding a white mug that says "CODE MODE ON"
   with steam, relaxed smile.
2. **Power nap**: leaning back in a large black bean-bag chair, hands behind his head, laptop on his lap, white
   sneakers up in the foreground, "z Z Z" floating above, content smile.
3. **New mechanical keyboard**: tongue out playfully, pointing with his right index finger at a black mechanical
   keyboard he holds up diagonally in his left hand, one lime-green key, small lime sparkle lines around it.
4. **Picking the stack**: classic thinking pose, his right hand under his chin as a relaxed loose fist, index
   finger curled along the jaw, thumb under the chin, clear separated fingers. Laptop in front, a small potted
   plant on the left, a stack of books on the right labelled Docs, StackOverflow, ChatGPT, YouTube,
   handwritten "React? Laravel? Node? Docker?" and question marks floating around him, slight smirk.
5. **Bug fix fuel**: eating noodles with chopsticks from a red cup that says "BUG FIX", noodles hanging to his
   mouth, laptop at the lower left.
6. **Plan, code, deploy, repeat**: leaning back in an office chair, hands behind his head, white sneakers up
   on the desk, a monitor behind him with a checklist: PLAN, CODE, DEPLOY, REPEAT, each with a lime-green tick,
   smirk.

## If something comes out wrong

- Headphones missing or different: "Add the same black headphones as the previous image, don't change
  anything else."
- Bad hand: "Only redraw his hand with correct anatomy, five separated fingers. Keep everything else the same."
- Background not flat: "Make the background plain flat #07070a with no border, frame or glow."

Save them as `pose-1.png` … `pose-6.png` and send the folder path. Claude will colour-check, convert and swap
them into the hero.
