package com.example.demo.domain.usecase.product;

import com.example.demo.domain.entities.product.IProductRepository;

public class DeleteProductUseCase {
    private final IProductRepository productRepository;

    public DeleteProductUseCase(IProductRepository productRepository) {
        this.productRepository = productRepository;
    }

    public void execute(Long productId) {
        productRepository.deleteById(productId);
    }
}
