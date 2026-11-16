import { test } from "node:test";
import assert from "node:assert/strict";
import { parseContact, contactSubject } from "../src/lib/contact.ts";

const details = {
  name: " Moisés ",
  email: " moises@example.com ",
  message: " Quiero conversar sobre una aplicación web. ",
};

test("classifies both kinds of inquiries and uses a static mail subject", () => {
  for (const intent of ["career", "project"]) {
    const parsed = parseContact({ ...details, intent });
    assert.equal(parsed.ok, true);
    assert.equal(parsed.value.intent, intent);
    assert.equal(parsed.value.email, "moises@example.com");
    assert.equal(parsed.value.name, "Moisés");
  }
  assert.notEqual(contactSubject("career"), contactSubject("project"));
});

test("accepts the previous form payload while rejecting unsupported new intents", () => {
  assert.equal(parseContact(details).value.intent, "general");
  assert.deepEqual(parseContact({ ...details, intent: "sales" }), { ok: false, code: "intent" });
});

test("rejects malformed or excessively long input without throwing", () => {
  assert.deepEqual(parseContact(null), { ok: false, code: "body" });
  assert.deepEqual(parseContact([]), { ok: false, code: "body" });
  assert.deepEqual(parseContact({ ...details, name: 123 }), { ok: false, code: "name" });
  assert.deepEqual(parseContact({ ...details, email: "bad email" }), { ok: false, code: "email" });
  assert.deepEqual(parseContact({ ...details, message: "a".repeat(5001) }), { ok: false, code: "message" });
});

test("silently ignores the existing honeypot and limits optional context", () => {
  assert.deepEqual(parseContact({ company: "bot" }), { ok: true, value: null });
  assert.deepEqual(parseContact({ ...details, context: "a".repeat(201) }), { ok: false, code: "context" });
});
