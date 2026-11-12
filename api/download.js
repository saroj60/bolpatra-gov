const fs = require('fs');
const path = require('path');

module.exports = (req, res) => {
  const url = req.url || '';
  const docId = String(req.query.docId || '');
  const alfId = String(req.query.alfId || '');
  const isFirstLink = docId.includes('90915527') || alfId.includes('0ef3b48a') || url.includes('90915527') || url.includes('0ef3b48a');
  
  const targetFile = isFirstLink ? 'School_Building_Hemganga.pdf' : 'Road_Upgrading_Masani.pdf';
  const filePath = path.join(process.cwd(), targetFile);
  
  if (fs.existsSync(filePath)) {
    const fileBuffer = fs.readFileSync(filePath);
    res.setHeader('Content-Type', 'application/pdf');
    res.setHeader('Content-Disposition', 'attachment; filename="Letter of Price Bid.pdf"');
    res.setHeader('Content-Length', fileBuffer.length);
    return res.status(200).send(fileBuffer);
  }
  
  res.status(404).send('File not found');
};
