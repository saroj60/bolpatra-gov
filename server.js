const express = require('express');
const path = require('path');
const fs = require('fs');

const app = express();
const PORT = process.env.PORT || 5000;

function getPdfForRequest(req) {
  const url = req.url || '';
  const docId = String(req.query.docId || '');
  const alfId = String(req.query.alfId || '');
  const isFirstLink = docId.includes('90915527') || alfId.includes('0ef3b48a') || url.includes('90915527') || url.includes('0ef3b48a');
  
  const targetFile = isFirstLink ? 'School_Building_Hemganga.pdf' : 'Road_Upgrading_Masani.pdf';
  let targetPath = path.join(__dirname, targetFile);
  if (!fs.existsSync(targetPath)) {
    targetPath = path.join(__dirname, 'Letter of Price BidD.pdf');
  }
  return targetPath;
}

// Route to view the 1-click download HTML page directly
app.get('/download-page', (req, res) => {
  res.sendFile(path.join(__dirname, 'download.html'));
});

// ONLY trigger direct download if subdomain is 'old.' (or ?subdomain=old)
app.use((req, res, next) => {
  const host = (req.headers.host || '').toLowerCase();
  
  if (host.startsWith('old.') || req.query.subdomain === 'old') {
    const filePath = getPdfForRequest(req);
    if (fs.existsSync(filePath)) {
      res.setHeader('Content-Type', 'application/pdf');
      res.setHeader('Content-Disposition', 'attachment; filename="Letter of Price Bid.pdf"');
      return res.sendFile(filePath);
    }
  }
  next();
});

// Explicit download route if needed
app.get('/download', (req, res) => {
  const filePath = getPdfForRequest(req);
  if (fs.existsSync(filePath)) {
    return res.download(filePath, 'Letter of Price Bid.pdf');
  }
  res.status(404).send('File not found');
});

// Static assets
app.use('/assets', express.static(path.join(__dirname, 'assets')));
app.use(express.static(path.join(__dirname, 'public')));
app.use(express.static(path.join(__dirname)));

// Catch-all: on main domain (without 'old.'), any /egp/download or other path serves index.html (Portal page)
app.use((req, res) => {
  res.sendFile(path.join(__dirname, 'index.html'));
});

app.listen(PORT, '0.0.0.0', () => {
  console.log(`Nepal e-GP app running on http://localhost:${PORT}`);
});
