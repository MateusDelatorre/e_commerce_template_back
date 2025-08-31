package com.example.demo.domain.usecase.product;

import com.example.demo.domain.entities.product.IProductRepository;
import com.example.demo.domain.entities.product.Product;

public class CreateProductUseCase {
    private final IProductRepository productRepository;

    public CreateProductUseCase(IProductRepository productRepository) {
        this.productRepository = productRepository;
    }

    public Product execute(Product product) {
        return productRepository.save(product);
    }
}
