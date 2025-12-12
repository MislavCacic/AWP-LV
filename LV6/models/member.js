const mongoose = require('mongoose');

const memberSchema = new mongoose.Schema({
  ime:      { type: String, required: true },
  prezime:  { type: String, required: true },
  uloga:    { type: String, required: true },
  project:  { type: mongoose.Schema.Types.ObjectId, ref: 'Project', required: true }
});

module.exports = mongoose.model('Member', memberSchema);
