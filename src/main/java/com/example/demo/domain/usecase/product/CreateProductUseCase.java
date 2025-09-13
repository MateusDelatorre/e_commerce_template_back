package com.example.demo.domain.usecase.product;

import com.example.demo.data.repositories.ProductRepository;
import com.example.demo.domain.entities.product.IProductRepository;
import com.example.demo.domain.entities.product.Product;
import com.example.demo.domain.usecase.product.dto.IProductRegistrationData;

public class CreateProductUseCase {
    private final IProductRepository productRepository;

    public CreateProductUseCase(IProductRepository productRepository) {
        this.productRepository = productRepository;
    }

    public Product execute(IProductRegistrationData data) {
        Product product = new Product(
            null,
            data.getDescription(),
            data.getName(),
            data.getPrice(),
            data.getStockQuantity(),
            data.getCategory(),
            data.getImageUrl(),
            data.getActive(),
            data.getTags(),
            null,
            null,
            data.getOptions()
        );
        return productRepository.save(product);
    }
}
