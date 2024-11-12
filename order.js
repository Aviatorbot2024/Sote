const fs = require('fs');
const path = require('path');

module.exports = (req, res) => {
  const orderData = req.body;

  // Caminho para o arquivo de pedidos
  const orderFile = path.join(__dirname, 'orders.json');

  // Lê os pedidos existentes
  fs.readFile(orderFile, 'utf8', (err, data) => {
    if (err && err.code !== 'ENOENT') {
      return res.status(500).json({ error: 'Erro ao ler o arquivo' });
    }

    let orders = [];
    if (data) {
      orders = JSON.parse(data);
    }

    // Adiciona o novo pedido
    const newOrder = {
      items: orderData.items,
      total: orderData.total,
      status: 'Pendente',
      whatsappNumber: orderData.whatsappNumber,
      created_at: new Date().toISOString(),
    };
    orders.push(newOrder);

    // Salva os pedidos no arquivo
    fs.writeFile(orderFile, JSON.stringify(orders, null, 2), (err) => {
      if (err) {
        return res.status(500).json({ error: 'Erro ao salvar o pedido' });
      }

      // Envia a mensagem para o WhatsApp
      const message = `Seu pedido foi confirmado! Itens: ${orderData.items}. Total: ${orderData.total} MT`;
      const url = `https://wa.me/${orderData.whatsappNumber}?text=${encodeURIComponent(message)}`;

      // Responde com a URL para o WhatsApp
      res.status(200).json({
        message: 'Pedido realizado com sucesso!',
        url: url,
      });
    });
  });
};
